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
          $search  = trim((string) $request->get('search'));

          $query = Publikasi::with(['photos', 'videos'])
              ->latest();

          // 🔍 SEARCH
          if ($search !== '') {
              $query->where(function ($q) use ($search) {
                  $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
              });
          }

          $data = $query->paginate($perPage);

          return response()->json([
              'success' => true,
              'message' => 'Daftar publikasi',
              'data'    => $data,
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
