<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class RegionController extends Controller
{
    /**
     * Get all provinces
     */
    public function provinces()
    {
        return response()->json([
            'status' => true,
            'data' => Province::orderBy('name')->get(['id', 'name','code']),
        ]);
    }

    /**
     * Get cities by province_id
     */
    public function cities(Request $request)
    {
        $request->validate([
            'province_id' => 'required|exists:indonesia_provinces,code',
        ]);

        return response()->json([
            'status' => true,
            'data' => City::where('province_code', $request->province_id)
                ->orderBy('name')
                ->get(['id', 'name','code']),
        ]);
    }

    /**
     * Get districts by city_id
     */
    public function districts(Request $request)
    {
        $request->validate([
            'city_id' => 'required|exists:indonesia_cities,code',
        ]);

        return response()->json([
            'status' => true,
            'data' => District::where('city_code', $request->city_id)
                ->orderBy('name')
                ->get(['id', 'name','code']),
        ]);
    }

    /**
     * Get villages by district_id
     */
    public function villages(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:indonesia_districts,code',
        ]);

        return response()->json([
            'status' => true,
            'data' => Village::where('district_code', $request->district_id)
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }
}
