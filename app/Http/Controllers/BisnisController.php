<?php

namespace App\Http\Controllers;

use App\Models\Bisnis;
use App\Models\BisnisMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BisnisController extends Controller
{
    /**
     * List semua bisnis + search
     */
    public function index(Request $request)
    {
        $query = Bisnis::with('media')->latest();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('kategori', 'like', '%' . $request->search . '%');
            });
        }

        $items = $query->paginate(10)->withQueryString();

        return view('pages.master.bisnis.index', compact('items'));
    }

    /**
     * Form create bisnis
     */
    public function create()
    {
        return view('pages.master.bisnis.create');
    }

    /**
     * Simpan bisnis + media
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'youtube_urls.*' => 'nullable|url',
            'alamat' => 'nullable|string|max:500',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        $bisnis = Bisnis::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'website' => $request->website,
            'is_active' => 1,
        ]);


        // Simpan gambar
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $img) {
                $path = $img->store('bisnis/images', 'public');

                BisnisMedia::create([
                    'bisnis_id' => $bisnis->id,
                    'type' => 'image',
                    'file_path' => $path,
                    'urutan' => $i,
                ]);
            }
        }

        // Simpan video youtube
        if ($request->youtube_urls) {
            foreach ($request->youtube_urls as $url) {
                if ($url) {
                    BisnisMedia::create([
                        'bisnis_id' => $bisnis->id,
                        'type' => 'youtube',
                        'url' => $url,
                    ]);
                }
            }
        }

        return redirect()
            ->route('bisnis.index')
            ->with('success', 'Bisnis berhasil ditambahkan');
    }

    /**
     * Detail bisnis
     */
    public function show($id)
    {
        $bisnis = Bisnis::with('media')->findOrFail($id);
        return view('pages.master.bisnis.show', compact('bisnis'));
    }

    /**
     * Form edit bisnis
     */
    public function edit($id)
    {
        $bisnis = Bisnis::with(['images', 'videos'])->findOrFail($id);
        return view('pages.master.bisnis.edit', compact('bisnis'));
    }

    /**
     * Update bisnis + tambah media baru
     */
    public function update(Request $request, $id)
    {
        $bisnis = Bisnis::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'kategori' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'youtube_urls.*' => 'nullable|url',
            'alamat' => 'nullable|string|max:500',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
        ]);

        $bisnis->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'website' => $request->website,
        ]);


        // Tambah foto baru (jika ada)
        if ($request->hasFile('images')) {
            $lastOrder = BisnisMedia::where('bisnis_id', $bisnis->id)
                ->where('type', 'image')
                ->max('urutan') ?? 0;

            foreach ($request->file('images') as $i => $img) {
                $path = $img->store('bisnis/images', 'public');

                BisnisMedia::create([
                    'bisnis_id' => $bisnis->id,
                    'type' => 'image',
                    'file_path' => $path,
                    'urutan' => $lastOrder + $i + 1,
                ]);
            }
        }

        // Tambah video baru
        if ($request->youtube_urls) {
            foreach ($request->youtube_urls as $url) {
                if ($url) {
                    BisnisMedia::create([
                        'bisnis_id' => $bisnis->id,
                        'type' => 'youtube',
                        'url' => $url,
                    ]);
                }
            }
        }

        return redirect()
            ->route('bisnis.index')
            ->with('success', 'Bisnis berhasil diperbarui');
    }

    /**
     * Hapus bisnis beserta media
     */
    public function destroy($id)
    {
        $bisnis = Bisnis::with('media')->findOrFail($id);

        foreach ($bisnis->media as $media) {
            if ($media->file_path) {
                Storage::disk('public')->delete($media->file_path);
            }
        }

        $bisnis->delete();

        return redirect()
            ->route('bisnis.index')
            ->with('success', 'Bisnis berhasil dihapus');
    }

    /**
     * Hapus satu media (foto / video)
     */
    public function destroyMedia($id)
    {
        $media = BisnisMedia::findOrFail($id);

        if ($media->file_path) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return back()->with('success', 'Media berhasil dihapus');
    }
}
