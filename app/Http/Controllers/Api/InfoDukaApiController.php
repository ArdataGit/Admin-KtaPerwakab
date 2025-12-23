<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InfoDuka;
use Illuminate\Http\Request;

class InfoDukaApiController extends Controller
{
    /**
     * LIST INFO DUKA
     * Support:
     * - search
     * - filter tahun
     */
    public function index(Request $request)
    {
        $query = InfoDuka::query()
            ->where('is_active', 1);

        /**
         * SEARCH
         */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('nama_almarhum', 'like', "%{$search}%");
            });
        }

        /**
         * FILTER TAHUN
         */
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_publish', $request->tahun);
        }

        /**
         * PAGINATION
         */
        $perPage = $request->integer('per_page', 10);

        $data = $query
            ->orderByDesc('tanggal_publish')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * DETAIL INFO DUKA
     */
    public function show($id)
    {
        $item = InfoDuka::where('is_active', 1)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $item,
        ]);
    }
}
