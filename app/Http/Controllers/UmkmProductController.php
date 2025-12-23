<?php

// app/Http/Controllers/Admin/UmkmProductController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\UmkmProduct;
use Illuminate\Http\Request;

class UmkmProductController extends Controller
{
    public function index(Umkm $umkm)
    {
        $products = $umkm->products()
            ->with('photos')->latest()->paginate(10);
        // dd($ products);
        return view('pages.master.umkm-product.index', compact('umkm', 'products'));
    }

    public function create(Umkm $umkm)
    {
        return view('admin.umkm-product.create', compact('umkm'));
    }

    public function store(Request $request, Umkm $umkm)
    {
        $data = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'youtube_link' => 'nullable|url',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $product = $umkm->products()->create($data);

        // SIMPAN FOTO
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('umkm/product', 'public');

                $product->photos()->create([
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()
            ->route('umkm.products.index', $umkm->id)
            ->with('success', 'Produk berhasil ditambahkan');
    }


    public function edit(Umkm $umkm, UmkmProduct $product)
    {
        return view('admin.umkm-product.edit', compact('umkm', 'product'));
    }

    public function update(Request $request, Umkm $umkm, UmkmProduct $product)
    {
        $data = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'youtube_link' => 'nullable|url',
            'photos.*' => 'nullable|image|max:2048',
        ]);

        $product->update($data);

        // TAMBAH FOTO BARU (tidak hapus lama)
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('umkm/product', 'public');

                $product->photos()->create([
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()
            ->route('umkm.products.index', $umkm->id)
            ->with('success', 'Produk berhasil diperbarui');
    }


    public function destroy(Umkm $umkm, UmkmProduct $product)
    {
        $product->delete();

        return back()->with('success', 'Produk berhasil dihapus');
    }
}
