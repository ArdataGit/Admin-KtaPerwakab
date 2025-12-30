<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UmkmProduct;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UmkmProductApiController extends Controller
{
    /**
     * List semua produk UMKM (PUBLIC - hanya yang approved)
     * Bisa difilter berdasarkan umkm_id / category
     */
    public function index(Request $request)
    {
        $query = UmkmProduct::with([
            'photos',
            'umkm.user'
        ])
        ->approved() // Hanya produk yang sudah disetujui
        ->latest('approved_at');

        // 🔍 SEARCH PRODUK / UMKM
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('umkm.user', function ($user) use ($search) {
                      $user->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 🏪 filter berdasarkan UMKM
        if ($request->filled('umkm_id')) {
            $query->where('umkm_id', $request->umkm_id);
        }

        // 🏷 filter berdasarkan kategori UMKM
        if ($request->filled('category')) {
            $query->whereHas('umkm', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(10),
        ]);
    }

    /**
     * List produk milik user yang sedang login (MY PRODUCTS)
     * Menampilkan semua status (pending, approved, rejected)
     */
    public function myProducts(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Cari UMKM milik user
        $umkm = Umkm::where('user_id', $user->id)->first();

        if (!$umkm) {
            return response()->json([
                'success' => true,
                'message' => 'Anda belum memiliki UMKM',
                'data' => []
            ]);
        }

        $query = UmkmProduct::with('photos')
            ->where('umkm_id', $umkm->id)
            ->latest();

        // Filter berdasarkan status (opsional)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json([
            'success' => true,
            'data' => $query->paginate(10),
            'umkm' => $umkm
        ]);
    }

    /**
     * Tambah produk baru (USER)
     * Status otomatis pending
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Cari UMKM milik user
        $umkm = Umkm::where('user_id', $user->id)->first();

        if (!$umkm) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki UMKM. Silakan daftar UMKM terlebih dahulu.'
            ], 403);
        }

        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'youtube_link' => 'nullable|url',
            'photos' => 'required|array|min:1',
            'photos.*' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        // Produk baru otomatis status pending
        $validated['status'] = 'pending';
        $validated['umkm_id'] = $umkm->id;

        $product = UmkmProduct::create($validated);

        // Simpan foto
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('umkm/product', 'public');

                $product->photos()->create([
                    'file_path' => $path,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan dan menunggu persetujuan admin',
            'data' => $product->load('photos')
        ], 201);
    }

    /**
     * Update produk (USER)
     * Hanya bisa update produk miliknya sendiri
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $umkm = Umkm::where('user_id', $user->id)->first();

        if (!$umkm) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki UMKM'
            ], 403);
        }

        $product = UmkmProduct::where('umkm_id', $umkm->id)
            ->findOrFail($id);

        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'youtube_link' => 'nullable|url',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        $product->update($validated);

        // Tambah foto baru jika ada
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('umkm/product', 'public');

                $product->photos()->create([
                    'file_path' => $path,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui',
            'data' => $product->load('photos')
        ]);
    }

    /**
     * Hapus produk (USER)
     * Hanya bisa hapus produk miliknya sendiri
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $umkm = Umkm::where('user_id', $user->id)->first();

        if (!$umkm) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki UMKM'
            ], 403);
        }

        $product = UmkmProduct::where('umkm_id', $umkm->id)
            ->findOrFail($id);

        // Hapus foto dari storage
        foreach ($product->photos as $photo) {
            Storage::disk('public')->delete($photo->file_path);
            $photo->delete();
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }

    /**
     * Hapus foto produk (USER)
     * Hanya bisa hapus foto dari produk miliknya sendiri
     */
    public function deletePhoto($photoId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $umkm = Umkm::where('user_id', $user->id)->first();

        if (!$umkm) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memiliki UMKM'
            ], 403);
        }

        // Cari foto dan pastikan milik user
        $photo = \App\Models\UmkmProductPhoto::whereHas('product', function($query) use ($umkm) {
            $query->where('umkm_id', $umkm->id);
        })->findOrFail($photoId);

        // Cek apakah produk masih punya foto lain
        $product = $photo->product;
        if ($product->photos()->count() <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menghapus foto. Produk harus memiliki minimal 1 foto.'
            ], 400);
        }

        // Hapus file dari storage
        Storage::disk('public')->delete($photo->file_path);
        
        // Hapus record dari database
        $photo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil dihapus'
        ]);
    }

    /**
     * Detail satu produk (PUBLIC - hanya yang approved)
     */
    public function show($id)
    {
        $product = UmkmProduct::with([
            'photos',
            'umkm.user'
        ])
        ->approved()
        ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }
}
