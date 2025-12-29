<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Models\TripayTransaction;
use App\Http\Services\TripayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Carbon\Carbon;


class DonationApiController extends Controller
{
    public function myDonations()
{
    /**
     * =====================================================
     * 1. AUTH CHECK (WAJIB)
     * =====================================================
     */
    $user = auth()->user();

    if (!$user) {
        Log::warning('myDonations: unauthenticated access');
        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated',
        ], 401);
    }

    /**
     * =====================================================
     * 2. QUERY DONATIONS (TIDAK BOLEH firstOrFail)
     * =====================================================
     */
    $donations = Donation::with('campaign')
        ->where('user_id', $user->id)
        ->orderByDesc('created_at')
        ->get();


    /**
     * =====================================================
     * 3. HITUNG TOTAL PAID
     * =====================================================
     */
    $totalPaid = $donations
        ->where('status', 'PAID')
        ->sum('amount');

    /**
     * =====================================================
     * 4. RESPONSE (SELALU 200, MESKI DATA KOSONG)
     * =====================================================
     */
    return response()->json([
        'success' => true,
        'data' => [
            'total_paid' => (int) $totalPaid,
            'donations' => $donations->map(function ($donation) {
                return [
                    'id' => $donation->id,
                    'campaign_title' => $donation->campaign?->title ?? '-',
                    'amount' => (int) $donation->amount,
                    'status' => $donation->status, // PAID | PENDING | UNPAID | EXPIRED
                    'created_at' => $donation->created_at->toDateTimeString(),
                ];
            })->values(),
        ],
    ]);
}


    /**
     * Create Donation + Tripay Transaction
     */
    public function store(Request $request, TripayService $tripay)
    {
        // =====================================================
        // 1. VALIDATION
        // =====================================================
        $data = $request->validate([
            'campaign_id'    => 'required|exists:donation_campaigns,id',
            'amount'         => 'required|numeric|min:20000',
            'payment_method' => 'required|string',
            'donor_name'     => 'nullable|string|max:191',
            'donor_email'    => 'nullable|email|max:191',
            'donor_phone'    => 'nullable|string|max:20',
            'is_anonymous'   => 'nullable|boolean',
        ]);

        // =====================================================
        // 2. TRANSACTION DB
        // =====================================================
        return DB::transaction(function () use ($data, $request, $tripay) {

            // -------------------------------------------------
            // Campaign aktif
            // -------------------------------------------------
            $campaign = DonationCampaign::query()
                ->where('is_active', true)
                ->findOrFail($data['campaign_id']);

            // -------------------------------------------------
            // Normalisasi amount (Tripay STRICT)
            // -------------------------------------------------
            $amount = (int) $data['amount'];

            // -------------------------------------------------
            // Simpan Donation
            // -------------------------------------------------
            $donation = Donation::create([
                'campaign_id' => $campaign->id,
                'user_id'     => $request->user()?->id,
                'donor_name'  => $data['donor_name'] ?? null,
                'donor_email' => $data['donor_email'] ?? null,
                'donor_phone' => $data['donor_phone'] ?? null,
                'is_anonymous'=> $request->boolean('is_anonymous'),
                'amount'      => $amount,
                'status'      => 'PENDING',
            ]);

            // -------------------------------------------------
            // Simpan Tripay Transaction (LOCAL)
            // -------------------------------------------------
            $merchantRef = 'DON-' . strtoupper(Str::random(10));

            $tripayTx = TripayTransaction::create([
                'merchant_ref'     => $merchantRef,
                'transaction_type' => 'donation',
                'related_id'       => $donation->id,
                'amount'           => $amount,
                'user_id'          => $donation->user_id,
                'customer_name'    => $donation->donor_name,
                'customer_email'   => $donation->donor_email,
                'customer_phone'   => $donation->donor_phone,
                'status'           => 'UNPAID',
                'is_dev'           => config('tripay.dev'),
            ]);

            // -------------------------------------------------
            // 3. PAYLOAD TRIPAY (STRICT FORMAT)
            // -------------------------------------------------
            $payload = [
                'method'        => $data['payment_method'],
                'merchant_ref'  => $merchantRef,
                'amount'        => $amount,
                'customer_name' => $donation->donor_name ?? 'Donatur',
                'customer_email'=> $donation->donor_email ?? 'donatur@donasi.id',
                'customer_phone'=> $donation->donor_phone,
                'order_items'   => [
                    [
                        'sku'      => 'DONATION',
                        'name'     => $campaign->title,
                        'price'    => $amount,
                        'quantity' => 1,
                    ],
                ],
                'callback_url' => config('tripay.callback_url'),
                'return_url'   => config('tripay.return_url'),
            ];

            // -------------------------------------------------
            // 4. CALL TRIPAY
            // -------------------------------------------------
            $response = $tripay->createTransaction($payload);

            if (!$response->successful()) {

                // LOG DETAIL ERROR (WAJIB)
                Log::error('Tripay create transaction failed', [
                    'payload' => $payload,
                    'status'  => $response->status(),
                    'body'    => $response->body(),
                    'json'    => $response->json(),
                ]);

                throw new HttpException(
                    400,
                    $response->json('message') ?? 'Gagal membuat transaksi pembayaran'
                );
            }

            $result = $response->json('data');

            // -------------------------------------------------
            // 5. UPDATE TRIPAY TRANSACTION
            // -------------------------------------------------
            $expiredAt = isset($result['expired_time'])
                ? Carbon::createFromTimestamp((int) $result['expired_time'])
                : null;

            $tripayTx->update([
                'tripay_reference' => $result['reference'],
                'payment_method'   => $result['payment_method'],
                'payment_name'     => $result['payment_name'] ?? null,
                'expired_at'       => $expiredAt,
                'tripay_payload'   => $response->json(),
            ]);

            // -------------------------------------------------
            // 6. RESPONSE
            // -------------------------------------------------
            return response()->json([
                'success' => true,
                'message' => 'Donasi berhasil dibuat',
                'data' => [
                    'donation_id' => $donation->id,
                    'amount'      => $amount,
                    'payment_url' => $result['checkout_url'],
                ],
            ]);
        });
    }
  
    public function show($id)
    {
        $donation = Donation::with(['campaign', 'tripayTransaction'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'campaign_name' => $donation->campaign->title,
                'amount' => $donation->amount,
                'payment_method' => $donation->tripayTransaction->payment_method,
                'payment_name' => $donation->tripayTransaction->payment_name,
                'pay_code' => $donation->tripayTransaction->tripay_payload['data']['pay_code'] ?? null,
                'account_name' => 'TRIPAY',
                'checkout_url' => $donation->tripayTransaction->tripay_payload['data']['checkout_url'],
                'expired_at' => $donation->tripayTransaction->expired_at,
            ]
        ]);
    }

}
