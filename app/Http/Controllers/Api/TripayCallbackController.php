<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TripayTransaction;
use App\Models\Donation;
use App\Models\UserPoint;
use App\Models\PointKategori;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TripayCallbackController extends Controller
{
    /**
     * Handle callback dari Tripay
     * URL ini dipanggil langsung oleh server Tripay
     */
    public function handle(Request $request)
    {
        /**
         * 1. Validasi signature (WAJIB)
         */
        $signature = hash_hmac(
            'sha256',
            $request->getContent(),
            config('tripay.private_key')
        );

        if ($signature !== $request->header('X-Callback-Signature')) {
            Log::warning('Tripay callback invalid signature', [
                'header' => $request->header('X-Callback-Signature')
            ]);

            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payload = $request->all();

        /**
         * 2. Ambil transaksi Tripay
         */
        $tripayTx = TripayTransaction::where(
            'tripay_reference',
            $payload['reference'] ?? null
        )->first();

        if (!$tripayTx) {
            Log::warning('Tripay transaction not found', $payload);
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        /**
         * 3. Update status Tripay Transaction
         */
        $tripayTx->update([
            'status' => $payload['status'],
            'paid_at' => $payload['status'] === 'PAID' ? now() : null,
            'tripay_payload' => $payload,
        ]);

        /**
         * 4. Sinkron ke domain DONASI
         */
        if ($tripayTx->transaction_type === 'donation') {
            $donation = Donation::find($tripayTx->related_id);

            if ($donation) {

                    DB::transaction(function () use ($payload, $donation) {

                        $newStatus = match ($payload['status']) {
                            'PAID'    => 'PAID',
                            'EXPIRED' => 'EXPIRED',
                            default   => 'FAILED',
                        };

                        // Jika sudah PAID sebelumnya, stop (anti double callback)
                        if ($donation->status === 'PAID') {
                            return;
                        }

                        $donation->update([
                            'status' => $newStatus
                        ]);

                        // ==================================================
                        // TAMBAH USER POINT JIKA:
                        // - Status PAID
                        // - Nominal >= 50.000
                        // ==================================================
                        if ($newStatus === 'PAID' && $donation->amount >= 50000) {

                            // Cek apakah poin kategori 6 sudah pernah dibuat
                            $alreadyRewarded = UserPoint::where('id_user', $donation->user_id)
                                ->where('id_category', 6)
                                ->whereBetween('created_at', [
                                    $donation->created_at->startOfMinute(),
                                    $donation->created_at->copy()->endOfMinute()
                                ])
                                ->exists();

                            if (!$alreadyRewarded) {

                                $category = PointKategori::find(6);

                                if ($category) {

                                    UserPoint::create([
                                        'id_category' => $category->id,
                                        'id_user'     => $donation->user_id,
                                        'created_by'  => $donation->user_id,
                                    ]);

                                    $donation->user()->increment('point', $category->point);
                                }
                            }
                        }
                    });
                }
        }

        /**
         * 5. Response wajib 200
         */
        return response()->json(['success' => true]);
    }
}
