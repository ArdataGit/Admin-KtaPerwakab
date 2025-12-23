<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UmkmProduct;
use Illuminate\Http\Request;

class UmkmProductApiController extends Controller
{
    /**
     * List semua produk UMKM
     * Bisa difilter berdasarkan umkm_id / category
     */
    public function index(Request $request)
    {
        $query = UmkmProduct::with([
            'photos',
            'umkm'
        ])->latest();

        // filter berdasarkan UMKM
        if ($request->filled('umkm_id')) {
            $query->where('umkm_id', $request->umkm_id);
        }

        // filter berdasarkan kategori UMKM
        if ($request->filled('category')) {
            $query->whereHas('umkm', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(10),
        ]);
    }

    /**
     * Detail satu produk
     */
    public function show($id)
    {
        $product = UmkmProduct::with([
            'photos',
            'umkm'
        ])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }
}
