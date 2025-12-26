<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TripayService;

class TripayApiController extends Controller
{
    /**
     * List payment method Tripay
     */
    public function paymentMethods(TripayService $tripay)
    {
        $response = $tripay->paymentMethods();

        if (!$response->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil payment method',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => $response->json('data'),
        ]);
    }
}
