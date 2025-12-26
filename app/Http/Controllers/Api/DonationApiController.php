<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Models\TripayTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\TripayService;

class DonationApiController extends Controller
{
    /**
     * Create Donation + Tripay Transaction
     */
    public function store(Request $request, TripayService $tripay)
    {
        $data = $request->validate([
            'campaign_id' => 'required|exists:donation_campaigns,id',
            'amount' => 'required|numeric|min:1000',
            'donor_name' => 'nullable|string|max:191',
            'donor_email' => 'nullable|email|max:191',
            'donor_phone' => 'nullable|string|max:20',
            'is_anonymous' => 'nullable|boolean',
            'payment_method' => 'required|string', // ex: BRIVA, QRIS, BCA
        ]);

        return DB::transaction(function () use ($data, $request, $tripay) {

            // 1. Ambil campaign
            $campaign = DonationCampaign::where('is_active', true)
                ->findOrFail($data['campaign_id']);

            // 2. Simpan donasi
            $donation = Donation::create([
                'campaign_id' => $campaign->id,
                'user_id' => $request->user()?->id,
                'donor_name' => $data['donor_name'] ?? null,
                'donor_email' => $data['donor_email'] ?? null,
                'donor_phone' => $data['donor_phone'] ?? null,
                'is_anonymous' => $request->boolean('is_anonymous'),
                'amount' => $data['amount'],
                'status' => 'PENDING',
            ]);

            // 3. Buat Tripay transaction (global)
            $merchantRef = 'DON-' . strtoupper(Str::random(10));

            $tripayTx = TripayTransaction::create([
                'merchant_ref' => $merchantRef,
                'transaction_type' => 'donation',
                'related_id' => $donation->id,
                'amount' => $donation->amount,
                'user_id' => $donation->user_id,
                'customer_name' => $donation->donor_name,
                'customer_email' => $donation->donor_email,
                'customer_phone' => $donation->donor_phone,
                'status' => 'UNPAID',
                'is_dev' => config('tripay.dev'),
            ]);

            // 4. Payload Tripay
            $payload = [
                'method' => $data['payment_method'],
                'merchant_ref' => $merchantRef,
                'amount' => $donation->amount,
                'customer_name' => $donation->donor_name ?? 'Donatur',
                'customer_email' => $donation->donor_email,
                'customer_phone' => $donation->donor_phone,
                'order_items' => [
                    [
                        'sku' => 'DONATION',
                        'name' => $campaign->title,
                        'price' => $donation->amount,
                        'quantity' => 1,
                    ]
                ],
                'callback_url' => config('tripay.callback_url'),
                'return_url' => config('tripay.return_url'),
            ];

            // 5. Create transaksi ke Tripay
            $response = $tripay->createTransaction($payload);

            if (!$response->successful()) {
                abort(400, 'Gagal membuat transaksi pembayaran');
            }

            $result = $response->json();

            // 6. Update Tripay transaction
            $tripayTx->update([
                'tripay_reference' => $result['data']['reference'],
                'payment_method' => $result['data']['payment_method'],
                'payment_name' => $result['data']['payment_name'] ?? null,
                'expired_at' => now()->addSeconds($result['data']['expired_time'] ?? 0),
                'tripay_payload' => $result,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Donasi berhasil dibuat',
                'data' => [
                    'donation_id' => $donation->id,
                    'amount' => $donation->amount,
                    'payment_url' => $result['data']['checkout_url'],
                ]
            ]);
        });
    }
}
