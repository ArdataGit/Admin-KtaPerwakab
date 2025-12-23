<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function index()
    {
        $posisis = Position::latest()->paginate(10);
        return view('pages.master.position.index', compact('posisis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Position::create([
            'name' => $request->name
        ]);

        return back()->with('success', 'Berhasil menambah posisi baru');
    }

    public function update(Request $request, Position $position)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $position->update([
            'name' => $request->name
        ]);

        return back()->with('success', 'Berhasil mengupdate posisi');
    }

    public function destroy(Position $position)
    {
        $position->delete();
        return back()->with('success', 'Berhasil menghapus posisi');
    }
}
