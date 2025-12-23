<?php

namespace App\Http\Controllers;

use App\Models\MasterPenukaranPoin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterPenukaranPoinController extends Controller
{
    public function index()
    {
        $items = MasterPenukaranPoin::latest()->paginate(10);

        return view(
            'pages.master.penukaran_poin.index',
            compact('items')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'jumlah_poin' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('penukaran-poin', 'public');
        }

        MasterPenukaranPoin::create([
            'produk' => $request->produk,
            'keterangan' => $request->keterangan,
            'jumlah_poin' => $request->jumlah_poin,
            'image' => $imagePath,
            'is_active' => true,
        ]);

        return back()->with('success', 'Berhasil menambah produk penukaran poin');
    }

    public function update(Request $request, MasterPenukaranPoin $masterPenukaranPoin)
    {
        $request->validate([
            'produk' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'jumlah_poin' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if (
                $masterPenukaranPoin->image &&
                Storage::disk('public')->exists($masterPenukaranPoin->image)
            ) {
                Storage::disk('public')->delete($masterPenukaranPoin->image);
            }

            $masterPenukaranPoin->image = $request->file('image')
                ->store('penukaran-poin', 'public');
        }

        $masterPenukaranPoin->update([
            'produk' => $request->produk,
            'keterangan' => $request->keterangan,
            'jumlah_poin' => $request->jumlah_poin,
        ]);

        return back()->with('success', 'Berhasil mengupdate produk penukaran poin');
    }

    public function destroy(MasterPenukaranPoin $masterPenukaranPoin)
    {
        if (
            $masterPenukaranPoin->image &&
            Storage::disk('public')->exists($masterPenukaranPoin->image)
        ) {
            Storage::disk('public')->delete($masterPenukaranPoin->image);
        }

        $masterPenukaranPoin->delete();

        return back()->with('success', 'Berhasil menghapus produk penukaran poin');
    }

    //API

    public function apiIndex(Request $request)
    {
        $query = MasterPenukaranPoin::query();

        // hanya yang aktif (default)
        if (!$request->has('with_inactive')) {
            $query->where('is_active', true);
        }

        // search produk
        if ($request->search) {
            $query->where('produk', 'like', '%' . $request->search . '%');
        }

        // sorting
        $query->orderBy(
            $request->get('sort_by', 'produk'),
            $request->get('sort_dir', 'asc')
        );

        // pagination (optional)
        if ($request->has('per_page')) {
            $data = $query->paginate((int) $request->per_page);
        } else {
            $data = $query->get();
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function apiDetail($id)
    {
        $data = MasterPenukaranPoin::where('id', $id)
            ->when(
                !request()->has('with_inactive'),
                fn($q) => $q->where('is_active', true)
            )
            ->first();

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data master penukaran poin tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


}
