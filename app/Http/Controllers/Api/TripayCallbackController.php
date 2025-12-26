<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TripayTransaction;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
                $donation->update([
                    'status' => match ($payload['status']) {
                        'PAID' => 'PAID',
                        'EXPIRED' => 'EXPIRED',
                        default => 'FAILED',
                    }
                ]);
            }
        }

        /**
         * 5. Response wajib 200
         */
        return response()->json(['success' => true]);
    }
}
