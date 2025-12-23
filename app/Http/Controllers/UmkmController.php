<?php

// app/Http/Controllers/Admin/UmkmController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UmkmController extends Controller
{
    public function index()
    {
        $umkms = Umkm::latest()->paginate(10);

        return view('pages.master.umkm.index', compact('umkms'));
    }

    public function create()
    {
        return view('admin.umkm.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'umkm_name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'logo' => 'nullable|image|max:2048',
            'contact_wa' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('umkm/logo', 'public');
        }

        Umkm::create($data);

        return redirect()
            ->route('umkm.index')
            ->with('success', 'UMKM berhasil ditambahkan');
    }

    public function edit(Umkm $umkm)
    {
        return view('admin.umkm.edit', compact('umkm'));
    }

    public function update(Request $request, Umkm $umkm)
    {
        $data = $request->validate([
            'umkm_name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'logo' => 'nullable|image|max:2048',
            'contact_wa' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('logo')) {
            if ($umkm->logo) {
                Storage::disk('public')->delete($umkm->logo);
            }
            $data['logo'] = $request->file('logo')->store('umkm/logo', 'public');
        }

        $umkm->update($data);

        return redirect()
            ->route('umkm.index')
            ->with('success', 'UMKM berhasil diperbarui');
    }

    public function destroy(Umkm $umkm)
    {
        if ($umkm->logo) {
            Storage::disk('public')->delete($umkm->logo);
        }

        $umkm->delete();

        return back()->with('success', 'UMKM berhasil dihapus');
    }
}
