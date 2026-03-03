<?php

namespace App\Http\Controllers;

use App\Models\OrganizationHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationHistoryController extends Controller
{
    /**
     * Halaman admin (edit single record)
     */
    public function index()
    {
        $history = OrganizationHistory::first();

        return view('pages.master.organization-history.index', compact('history'));
    }

    /**
     * Simpan / Update (Single Record)
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

        // Upload gambar jika ada
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
            OrganizationHistory::create($data);
        }

        return redirect()
            ->back()
            ->with('success', 'Sejarah organisasi berhasil disimpan');
    }

    /**
     * Tampilkan di frontend
     */
    public function show()
    {
        $history = OrganizationHistory::where('is_active', 1)->first();

        return view('organization.history', compact('history'));
    }
}