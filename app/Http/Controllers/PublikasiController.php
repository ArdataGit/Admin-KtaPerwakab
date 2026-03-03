<?php

namespace App\Http\Controllers;

use App\Models\Publikasi;
use App\Models\PublikasiPhoto;
use App\Models\PublikasiVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Publikasi::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('creator', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $publikasi = $query->latest()->paginate(10)->withQueryString();
        return view('pages.master.publikasi.index', compact('publikasi'));
    }

    public function create()
    {
        return view('pages.master.publikasi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'creator' => 'required',
            'description' => 'nullable',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'videos.*' => 'nullable|string',
        ]);

        $publikasi = Publikasi::create($validated);

        // Save Photos
        if ($request->hasFile('photos')) {
            foreach ($request->photos as $photo) {
                $path = $photo->store('publikasi', 'public');
                PublikasiPhoto::create([
                    'publikasi_id' => $publikasi->id,
                    'file_path' => $path
                ]);
            }
        }

        // Save Video Links
        if ($request->videos) {
            foreach ($request->videos as $link) {
                if ($link) {
                    PublikasiVideo::create([
                        'publikasi_id' => $publikasi->id,
                        'link' => $link,
                    ]);
                }
            }
        }

        return redirect()->route('publikasi.index')->with('success', 'Publikasi berhasil ditambahkan');
    }

    public function show($id)
    {
        $data = Publikasi::with('photos', 'videos')->findOrFail($id);
        return view('pages.master.publikasi.show', compact('data'));
    }

    public function edit($id)
    {
        $data = Publikasi::with('photos', 'videos')->findOrFail($id);
        return view('pages.master.publikasi.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $publikasi = Publikasi::findOrFail($id);

        $publikasi->update($request->only(['title', 'creator', 'description']));

        // Upload new photos
        if ($request->hasFile('photos')) {
            foreach ($request->photos as $photo) {
                $path = $photo->store('publikasi', 'public');
                PublikasiPhoto::create([
                    'publikasi_id' => $id,
                    'file_path' => $path
                ]);
            }
        }

        // Add new videos
        if ($request->videos) {
            foreach ($request->videos as $link) {
                if ($link) {
                    PublikasiVideo::create([
                        'publikasi_id' => $id,
                        'link' => $link
                    ]);
                }
            }
        }

        return redirect()->route('publikasi.index')->with('success', 'Publikasi diperbarui');
    }

    public function destroy($id)
    {
        $data = Publikasi::findOrFail($id);

        // Delete photos
        foreach ($data->photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
        }

        // Delete videos
        $data->videos()->delete();

        $data->delete();

        return redirect()->back()->with('success', 'Publikasi dihapus');
    }

    public function deletePhoto($photoId)
    {
        $photo = PublikasiPhoto::findOrFail($photoId);
        Storage::disk('public')->delete($photo->file_path);
        $photo->delete();

        return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus']);
    }

    public function deleteVideo($videoId)
    {
        $video = PublikasiVideo::findOrFail($videoId);
        $video->delete();

        return response()->json(['success' => true, 'message' => 'Video berhasil dihapus']);
    }
}
