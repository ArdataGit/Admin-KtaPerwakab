<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;

class HomeBannerApiController extends Controller
{
    public function index()
    {
        $banners = HomeBanner::orderBy('position')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'subtitle' => $item->subtitle,
                    'image' => $item->image,
                    'link' => $item->link,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $banners,
        ]);
    }
}
