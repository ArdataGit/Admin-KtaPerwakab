<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publikasi;
use Illuminate\Http\Request;

class PublikasiApiController extends Controller
{
    /**
     * GET /api/publikasi
     * List publikasi (paginated)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $data = Publikasi::with(['photos', 'videos'])
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Daftar publikasi',
            'data' => $data
        ]);
    }

    /**
     * GET /api/publikasi/{id}
     * Detail publikasi
     */
    public function show($id)
    {
        $data = Publikasi::with(['photos', 'videos'])->find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Publikasi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail publikasi',
            'data' => $data
        ]);
    }
}
