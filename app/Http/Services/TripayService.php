<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class TripayService
{
    protected string $apiKey;
    protected string $privateKey;
    protected string $merchantCode;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey       = config('tripay.api_key');
        $this->privateKey   = config('tripay.private_key');
        $this->merchantCode = config('tripay.merchant_code');
        $this->baseUrl      = config('tripay.base_url');
    }

    /**
     * GET payment methods
     */
    public function paymentMethods()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($this->baseUrl . '/merchant/payment-channel');
    }

    /**
     * CREATE TRANSACTION
     */
    public function createTransaction(array $payload)
    {
        /**
         * Tripay signature format (WAJIB):
         * hash_hmac('sha256', merchantCode + merchantRef + amount, privateKey)
         */
        $signature = hash_hmac(
            'sha256',
            $this->merchantCode .
            $payload['merchant_ref'] .
            $payload['amount'],
            $this->privateKey
        );

        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->asForm()->post(
            $this->baseUrl . '/transaction/create',
            array_merge($payload, [
                'signature' => $signature,
            ])
        );
    }
}
