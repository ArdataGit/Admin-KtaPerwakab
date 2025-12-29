<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StrukturOrganisasi;

class StrukturOrganisasiApiController extends Controller
{
    public function show()
    {
        $data = StrukturOrganisasi::first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Struktur organisasi belum tersedia'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $data->id,
                'judul' => $data->judul,
                'deskripsi' => $data->deskripsi,
                'file_url' => $data->file_path ? asset('storage/' . $data->file_path) : null,
                'updated_at' => $data->updated_at->format('Y-m-d H:i:s')
            ]
        ]);
    }
}
