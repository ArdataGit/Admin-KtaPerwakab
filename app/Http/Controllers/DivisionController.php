<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    public function index()
    {
        $divisions = Division::latest()->paginate(10);
        return view('pages.master.division.index', compact('divisions'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        Division::create(['name' => $request->name]);

        return back()->with('success', 'Berhasil menambah divisi');
    }

    public function update(Request $request, Division $division)
    {
        $request->validate(['name' => 'required']);
        $division->update(['name' => $request->name]);

        return back()->with('success', 'Berhasil mengupdate divisi');
    }

    public function destroy(Division $division)
    {
        $division->delete();
        return back()->with('success', 'Berhasil menghapus divisi');
    }
}

