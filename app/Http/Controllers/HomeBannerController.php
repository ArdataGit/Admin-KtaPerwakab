<?php

namespace App\Http\Controllers;

use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeBannerController extends Controller
{
    public function index()
    {
        $banners = HomeBanner::orderBy('position')
            ->paginate(10); // ⬅️ jumlah per halaman bebas

        return view('pages.home-banner.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:191',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'required|image|max:2048',
            'link' => 'nullable|string|max:255',
            'position' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        HomeBanner::create($data);

        return redirect()->back()->with('success', 'Banner berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $banner = HomeBanner::findOrFail($id);

        $data = $request->validate([
            'title' => 'nullable|string|max:191',
            'subtitle' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'link' => 'nullable|string|max:255',
            'position' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
        ]);

        if ($request->hasFile('image')) {
            if ($banner->image) {
                Storage::disk('public')->delete($banner->image);
            }
            $data['image'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->back()->with('success', 'Banner berhasil diperbarui');
    }

    public function destroy($id)
    {
        $banner = HomeBanner::findOrFail($id);

        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        return redirect()->back()->with('success', 'Banner berhasil dihapus');
    }
}
