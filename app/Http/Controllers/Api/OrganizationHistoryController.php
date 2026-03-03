<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrganizationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationHistoryController extends Controller
{
    /**
     * GET: Ambil sejarah aktif
     */
    public function show()
    {
        $history = OrganizationHistory::where('is_active', 1)->first();

        if (!$history) {
            return response()->json([
                'success' => false,
                'message' => 'Data sejarah belum tersedia'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $history->id,
                'title' => $history->title,
                'content' => $history->content,
                'featured_image' => $history->featured_image
                    ? asset('storage/' . $history->featured_image)
                    : null,
                'meta_description' => $history->meta_description,
                'updated_at' => $history->updated_at,
            ]
        ]);
    }

    /**
     * POST: Simpan / Update sejarah
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'meta_description' => 'nullable|string|max:255',
        ]);

        $history = OrganizationHistory::first();

        $data = $request->only([
            'title',
            'content',
            'meta_description',
        ]);

        if ($request->hasFile('featured_image')) {

            if ($history && $history->featured_image) {
                Storage::disk('public')->delete($history->featured_image);
            }

            $data['featured_image'] = $request
                ->file('featured_image')
                ->store('organization/history', 'public');
        }

        if ($history) {
            $history->update($data);
        } else {
            $data['is_active'] = 1;
            $history = OrganizationHistory::create($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sejarah berhasil disimpan',
            'data' => $history
        ]);
    }
}