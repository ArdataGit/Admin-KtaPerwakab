<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DonationCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DonationCampaignApiController extends Controller
{
    /**
     * List Campaign Donasi (Public)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $campaigns = DonationCampaign::query()
            ->where('is_active', true)
            ->orderByDesc('start_date')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'List campaign donasi',
            'data' => [
                'campaigns' => $campaigns->items(),
                'pagination' => [
                    'current_page' => $campaigns->currentPage(),
                    'last_page' => $campaigns->lastPage(),
                    'per_page' => $campaigns->perPage(),
                    'total' => $campaigns->total(),
                ],
            ],
        ]);

    }

    /**
     * Detail Campaign Donasi
     */
    public function show($id)
    {
        $campaign = DonationCampaign::where('is_active', true)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Detail campaign donasi',
            'data' => [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'description' => $campaign->description,
                'thumbnail' => $campaign->thumbnail
                    ? asset('storage/' . $campaign->thumbnail)
                    : null,
                'start_date' => $campaign->start_date?->format('Y-m-d'),
                'end_date' => $campaign->end_date?->format('Y-m-d'),
                'is_active' => $campaign->is_active,
            ],
        ]);
    }
}
