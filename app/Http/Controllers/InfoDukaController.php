<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\InfoDuka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InfoDukaController extends Controller
{
    public function index()
    {
        $items = InfoDuka::latest('tanggal_publish')->paginate(10);
        return view('pages.master.info-duka.index', compact('items'));
    }

    public function create()
    {
        return view('pages.master.info-duka.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_almarhum' => 'required|string|max:191',
            'usia' => 'nullable|integer',
            'asal' => 'nullable|string|max:191',
            'foto' => 'nullable|image|max:2048',
            'tanggal_wafat' => 'required|date',
            'tanggal_publish' => 'required|date',
            'judul' => 'required|string|max:191',
            'isi' => 'required|string',
            'rumah_duka' => 'nullable|string|max:191',
            'alamat_rumah_duka' => 'nullable|string',
            'jenis_pemakaman' => 'nullable|in:dikubur,dikremasi',
            'lokasi_pemakaman' => 'nullable|string|max:191',
            'waktu_pemakaman' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('info-duka', 'public');
        }

        $data['created_by'] = Auth::id();

        InfoDuka::create($data);

        return redirect()
            ->route('info-duka.index')
            ->with('success', 'Info duka berhasil ditambahkan');
    }

    public function edit(InfoDuka $infoDuka)
    {
        return view('pages.master.info-duka.edit', compact('infoDuka'));
    }

    public function update(Request $request, InfoDuka $infoDuka)
    {
        $data = $request->validate([
            'nama_almarhum' => 'required|string|max:191',
            'usia' => 'nullable|integer',
            'asal' => 'nullable|string|max:191',
            'foto' => 'nullable|image|max:2048',
            'tanggal_wafat' => 'required|date',
            'tanggal_publish' => 'required|date',
            'judul' => 'required|string|max:191',
            'isi' => 'required|string',
            'rumah_duka' => 'nullable|string|max:191',
            'alamat_rumah_duka' => 'nullable|string',
            'jenis_pemakaman' => 'nullable|in:dikubur,dikremasi',
            'lokasi_pemakaman' => 'nullable|string|max:191',
            'waktu_pemakaman' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('foto')) {
            if ($infoDuka->foto) {
                Storage::disk('public')->delete($infoDuka->foto);
            }
            $data['foto'] = $request->file('foto')->store('info-duka', 'public');
        }

        $infoDuka->update($data);

        return redirect()
            ->route('info-duka.index')
            ->with('success', 'Info duka berhasil diperbarui');
    }

    public function destroy($id)
    {
        $infoDuka = InfoDuka::findOrFail($id);

        try {

            if ($infoDuka->foto && Storage::disk('public')->exists($infoDuka->foto)) {
                Storage::disk('public')->delete($infoDuka->foto);
            }

            $infoDuka->delete();

            return back()->with('success', 'Info duka berhasil dihapus');

        } catch (\Throwable $e) {

            \Log::error('Gagal menghapus info duka', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat menghapus data');
        }
    }


}
