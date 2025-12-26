<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TripayService
{
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('tripay.base_url');
        $this->apiKey = config('tripay.api_key');
    }

    protected function request()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
        ]);
    }

    /**
     * Ambil metode pembayaran Tripay
     */
    public function paymentMethods()
    {
        return $this->request()
            ->get($this->baseUrl . '/merchant/payment-channel');
    }

    /**
     * Create transaksi Tripay
     */
    public function createTransaction(array $payload)
    {
        return $this->request()
            ->post($this->baseUrl . '/transaction/create', $payload);
    }
}
