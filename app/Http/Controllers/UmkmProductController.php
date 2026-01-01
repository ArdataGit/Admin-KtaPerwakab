<?php

// app/Http/Controllers/Admin/UmkmProductController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Umkm;
use App\Models\UmkmProduct;
use Illuminate\Http\Request;

class UmkmProductController extends Controller
{
    /**
     * Tampilkan semua produk dari semua UMKM (untuk admin)
     */
    public function indexAll()
    {
        $products = UmkmProduct::with(['umkm.user', 'photos'])
            ->latest()
            ->paginate(10);
        
        return view('pages.master.umkm-product.index-all', compact('products'));
    }

    public function index(Umkm $umkm)
    {
        $umkm->load('user'); // Eager load relasi user
        $products = $umkm->products()
            ->with('photos')->latest()->paginate(10);
        // dd($ products);
        return view('pages.master.umkm-product.index', compact('umkm', 'products'));
    }

    public function create(Umkm $umkm)
    {
        return view('pages.master.umkm-product.create', compact('umkm'));
    }

    public function store(Request $request, Umkm $umkm)
    {
        $data = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'youtube_link' => 'nullable|url',
            'photos' => 'required|array|min:1',
            'photos.*' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'price.required' => 'Harga produk wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'price.min' => 'Harga tidak boleh negatif',
            'photos.required' => 'Minimal harus upload 1 foto produk',
            'photos.min' => 'Minimal harus upload 1 foto produk',
            'photos.*.image' => 'File harus berupa gambar',
            'photos.*.mimes' => 'Format foto harus: JPEG, JPG, atau PNG',
            'photos.*.max' => 'Ukuran foto maksimal 2MB',
        ]);

        // Produk baru otomatis status pending
        $data['status'] = 'pending';

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
            ->with('success', 'Produk berhasil ditambahkan dan menunggu persetujuan admin');
    }


    public function edit(Umkm $umkm, UmkmProduct $product)
    {
        return view('pages.master.umkm-product.edit', compact('umkm', 'product'));
    }

    public function update(Request $request, Umkm $umkm, UmkmProduct $product)
    {
        // Debug: Test apakah method ini dipanggil
        // dd([
        //     'method_called' => 'UPDATE METHOD DIPANGGIL!',
        //     'umkm_id' => $umkm->id,
        //     'product_id' => $product->id,
        //     'request_all' => $request->all(),
        //     'request_method' => $request->method(),
        // ]);

        // Validasi: foto wajib jika produk belum punya foto sama sekali
        $photoRules = $product->photos()->count() > 0 
            ? 'nullable|array' 
            : 'required|array|min:1';
        
        $photoItemRules = $product->photos()->count() > 0 
            ? 'nullable|image|mimes:jpeg,jpg,png|max:2048'
            : 'required|image|mimes:jpeg,jpg,png|max:2048';

        $data = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'youtube_link' => 'nullable|url',
            'photos' => $photoRules,
            'photos.*' => $photoItemRules,
        ], [
            'product_name.required' => 'Nama produk wajib diisi',
            'price.required' => 'Harga produk wajib diisi',
            'price.numeric' => 'Harga harus berupa angka',
            'price.min' => 'Harga tidak boleh negatif',
            'photos.required' => 'Produk harus memiliki minimal 1 foto',
            'photos.min' => 'Minimal harus upload 1 foto produk',
            'photos.*.image' => 'File harus berupa gambar',
            'photos.*.mimes' => 'Format foto harus: JPEG, JPG, atau PNG',
            'photos.*.max' => 'Ukuran foto maksimal 2MB',
        ]);

        // Update hanya field yang bukan foto
        $product->update([
            'product_name' => $request->product_name,
            'price' => $request->price,
            'description' => $request->description,
            'youtube_link' => $request->youtube_link,
        ]);

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

    // Method baru untuk approve produk
    public function approve(Umkm $umkm, UmkmProduct $product)
    {
        $product->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Produk berhasil disetujui');
    }

    // Method baru untuk reject produk
    public function reject(Umkm $umkm, UmkmProduct $product)
    {
        $product->update([
            'status' => 'rejected',
            'approved_at' => null,
        ]);

        return back()->with('success', 'Produk ditolak');
    }
}
