<?php

// app/Http/Controllers/Admin/UmkmController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UmkmController extends Controller
{
    public function index()
    {
        $umkms = Umkm::with('user')->latest()->paginate(10);

        return view('pages.master.umkm.index', compact('umkms'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('umkm')->get();
        return view('pages.master.umkm.create', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:umkm,user_id',
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
        $users = User::whereDoesntHave('umkm')->get();
        return view('pages.master.umkm.edit', compact('umkm', 'users'));
    }

    public function update(Request $request, Umkm $umkm)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id|unique:umkm,user_id,' . $umkm->id,
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
