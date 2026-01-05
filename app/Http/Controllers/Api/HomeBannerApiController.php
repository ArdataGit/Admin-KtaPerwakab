<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;

class HomeBannerApiController extends Controller
{
    public function index()
    {
        $banners = HomeBanner::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('start_at')
                    ->orWhere('start_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('end_at')
                    ->orWhere('end_at', '>=', now());
            })
            ->orderBy('position')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'subtitle' => $item->subtitle,
                    'description' => $item->description,
                    'image' => $item->image ? asset('storage/' . $item->image) : null,
                    'position' => $item->position,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Data banner berhasil diambil',
            'data' => $banners,
            'total' => $banners->count(),
        ], 200);
    }
}
