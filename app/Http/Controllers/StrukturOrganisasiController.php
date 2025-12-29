<?php

namespace App\Http\Controllers;

use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        $data = StrukturOrganisasi::first();
        return view('pages.master.struktur-organisasi.index', compact('data'));
    }

    public function create()
    {
        // Cek apakah sudah ada data
        $exists = StrukturOrganisasi::exists();
        
        if ($exists) {
            return redirect()->route('struktur-organisasi.index')
                ->with('error', 'Struktur organisasi sudah ada. Silakan edit data yang ada.');
        }
        
        return view('pages.master.struktur-organisasi.create');
    }

    public function store(Request $request)
    {
        // Cek apakah sudah ada data
        if (StrukturOrganisasi::exists()) {
            return redirect()->route('struktur-organisasi.index')
                ->with('error', 'Struktur organisasi sudah ada.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $data = [
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ];

        // Upload file jika ada
        if ($request->hasFile('file')) {
            $path = $request->file('file')->store('struktur-organisasi', 'public');
            $data['file_path'] = $path;
        }

        StrukturOrganisasi::create($data);

        return redirect()->route('struktur-organisasi.index')
            ->with('success', 'Struktur organisasi berhasil dibuat');
    }

    public function edit()
    {
        $data = StrukturOrganisasi::first();
        
        if (!$data) {
            return redirect()->route('struktur-organisasi.create');
        }
        
        return view('pages.master.struktur-organisasi.edit', compact('data'));
    }

    public function update(Request $request)
    {
        $data = StrukturOrganisasi::first();
        
        if (!$data) {
            return redirect()->route('struktur-organisasi.create')
                ->with('error', 'Data tidak ditemukan');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $updateData = [
            'judul' => $validated['judul'],
            'deskripsi' => $validated['deskripsi'] ?? null,
        ];

        // Upload file baru jika ada
        if ($request->hasFile('file')) {
            // Hapus file lama
            if ($data->file_path) {
                Storage::disk('public')->delete($data->file_path);
            }
            
            $path = $request->file('file')->store('struktur-organisasi', 'public');
            $updateData['file_path'] = $path;
        }

        $data->update($updateData);

        return redirect()->route('struktur-organisasi.index')
            ->with('success', 'Struktur organisasi berhasil diperbarui');
    }

    public function destroy()
    {
        $data = StrukturOrganisasi::first();
        
        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        // Hapus file jika ada
        if ($data->file_path) {
            Storage::disk('public')->delete($data->file_path);
        }

        $data->delete();

        return redirect()->route('struktur-organisasi.index')
            ->with('success', 'Struktur organisasi berhasil dihapus');
    }
}
