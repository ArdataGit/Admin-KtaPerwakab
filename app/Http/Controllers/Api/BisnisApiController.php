<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bisnis;
use Illuminate\Http\Request;

class BisnisApiController extends Controller
{
  /**
   * GET /api/bisnis
   * ?search=kuliner
   */
  public function index(Request $request)
  {
      $query = Bisnis::with('media')
          ->where('is_active', 1);

      // 🔍 SEARCH
      if ($request->filled('search')) {
          $search = $request->search;

          $query->where(function ($q) use ($search) {
              $q->where('nama', 'like', "%{$search}%")
                ->orWhere('kategori', 'like', "%{$search}%")
                ->orWhere('alamat', 'like', "%{$search}%");
          });
      }
    
        if ($request->filled('category')) {
        $query->where('kategori', $request->category);
   		}

      $items = $query
          ->latest()
          ->get();

      return response()->json([
          'success' => true,
          'data' => $items
      ]);
  }


    /**
     * GET /api/bisnis/{slug}
     */
    public function show($slug)
    {
        $bisnis = Bisnis::with('media')
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->first();

        if (!$bisnis) {
            return response()->json([
                'success' => false,
                'message' => 'Bisnis tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $bisnis
        ]);
    }
}
