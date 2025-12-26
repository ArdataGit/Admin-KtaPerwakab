<?php

namespace App\Http\Controllers;

use App\Models\DonationCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DonationCampaignController extends Controller
{
    /**
     * List Campaign + Modal Create & Edit
     */
    public function index()
    {
        $items = DonationCampaign::latest()->paginate(10);

        return view('pages.master.donation-campaign.index', compact('items'));
    }

    /**
     * Store Campaign (Create via Modal)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['created_by'] = auth()->id();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')
                ->store('donation-campaigns', 'public');
        }

        DonationCampaign::create($data);

        return redirect()
            ->route('master.donation-campaign.index')
            ->with('success', 'Campaign donasi berhasil dibuat');
    }

    /**
     * Update Campaign (Edit via Modal)
     */
    public function update(Request $request, DonationCampaign $donationCampaign)
    {
        $data = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('thumbnail')) {
            if (
                $donationCampaign->thumbnail &&
                Storage::disk('public')->exists($donationCampaign->thumbnail)
            ) {
                Storage::disk('public')->delete($donationCampaign->thumbnail);
            }

            $data['thumbnail'] = $request->file('thumbnail')
                ->store('donation-campaigns', 'public');
        }

        $donationCampaign->update($data);

        return redirect()
            ->route('master.donation-campaign.index')
            ->with('success', 'Campaign donasi berhasil diperbarui');
    }

    /**
     * Delete Campaign
     */
    public function destroy(DonationCampaign $donationCampaign)
    {
        if (
            $donationCampaign->thumbnail &&
            Storage::disk('public')->exists($donationCampaign->thumbnail)
        ) {
            Storage::disk('public')->delete($donationCampaign->thumbnail);
        }

        $donationCampaign->delete();

        return back()->with('success', 'Campaign donasi berhasil dihapus');
    }
}
