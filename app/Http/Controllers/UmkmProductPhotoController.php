<?php

// app/Http/Controllers/Admin/UmkmProductPhotoController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\UmkmProduct;
use App\Models\UmkmProductPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UmkmProductPhotoController extends Controller
{
    public function store(Request $request, UmkmProduct $product)
    {
        $request->validate([
            'photos.*' => 'required|image|max:2048',
        ]);

        foreach ($request->file('photos') as $photo) {
            $path = $photo->store('umkm/product', 'public');

            $product->photos()->create([
                'file_path' => $path,
            ]);
        }

        return back()->with('success', 'Foto produk berhasil ditambahkan');
    }

    public function destroy(UmkmProductPhoto $photo)
    {
        Storage::disk('public')->delete($photo->file_path);
        $photo->delete();

        return back()->with('success', 'Foto produk berhasil dihapus');
    }
}
