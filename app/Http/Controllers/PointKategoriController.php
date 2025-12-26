<?php

namespace App\Http\Controllers;

use App\Models\PointKategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;

class PointKategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PointKategori::query()
            ->when($request->search, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });

        $pointKategoris = $query->paginate(15)->withQueryString(); // Persist search di pagination

        $users = User::where('role', 'anggota')->get();

        return view('pages.master.point-kategoris.index', compact('pointKategoris', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:point_kategori,name'],
            'point' => ['required', 'integer', 'min:0'],
        ]);

        PointKategori::create($validated);

        return redirect()->route('point-kategoris.index')->with('success', 'Point kategori created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(PointKategori $pointKategori)
    {
        $pointKategori->loadCount('userPoints'); // Hitung jumlah user_point terkait (opsional)

        return redirect()->route('point-kategoris.index'); // Atau buat view detail jika diperlukan
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PointKategori $pointKategori): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('point_kategori')->ignore($pointKategori->id)],
            'point' => ['sometimes', 'integer', 'min:0'],
        ]);

        $pointKategori->update($validated);

        return redirect()->route('point-kategoris.index')->with('success', 'Point kategori updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PointKategori $pointKategori): RedirectResponse
    {
        // Opsional: Cek apakah ada user_point terkait sebelum delete
        if ($pointKategori->userPoints()->count() > 0) {
            return back()->with('error', 'Cannot delete point kategori because it has associated user points');
        }

        $pointKategori->delete();

        return back()->with('success', 'Point kategori deleted successfully');
    }
}