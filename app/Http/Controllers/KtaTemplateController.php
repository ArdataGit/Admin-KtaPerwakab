<?php

namespace App\Http\Controllers;

use App\Models\KtaTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class KtaTemplateController
 *
 * Handles web CRUD for KTA Templates (Blade View).
 */
class KtaTemplateController extends Controller
{
    /**
     * Display list of templates.
     */
    public function index(Request $request)
    {
        $query = KtaTemplate::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $templates = $query->latest()->paginate(10);

        return view('pages.master.kta_templates.index', compact('templates'));
    }
    /**
     * Show create form.
     */
    public function create()
    {
        return view('kta_templates.create');
    }

    /**
     * Store new template.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:150',
            'front_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'back_image'  => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $frontPath = $request->file('front_image')
            ->store('kta_templates', 'public');

        $backPath = $request->file('back_image')
            ->store('kta_templates', 'public');

        KtaTemplate::create([
            'name'        => $request->name,
            'front_image' => $frontPath,
            'back_image'  => $backPath,
        ]);

        return redirect()
            ->route('kta-templates.index')
            ->with('success', 'Template berhasil ditambahkan.');
    }

    /**
     * Show edit form.
     */
    public function edit($id)
    {
        $template = KtaTemplate::findOrFail($id);

        return view('kta_templates.edit', compact('template'));
    }

    /**
     * Update template.
     */
    public function update(Request $request, $id)
    {
        $template = KtaTemplate::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:150',
            'front_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'back_image'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('front_image')) {
            Storage::disk('public')->delete($template->front_image);

            $template->front_image = $request->file('front_image')
                ->store('kta_templates', 'public');
        }

        if ($request->hasFile('back_image')) {
            Storage::disk('public')->delete($template->back_image);

            $template->back_image = $request->file('back_image')
                ->store('kta_templates', 'public');
        }

        $template->name = $request->name;
        $template->save();

        return redirect()
            ->route('kta-templates.index')
            ->with('success', 'Template berhasil diperbarui.');
    }

    /**
     * Delete template.
     */
    public function destroy($id)
    {
        $template = KtaTemplate::findOrFail($id);

        Storage::disk('public')->delete($template->front_image);
        Storage::disk('public')->delete($template->back_image);

        $template->delete();

        return redirect()
            ->route('kta-templates.index')
            ->with('success', 'Template berhasil dihapus.');
    }
  
    public function activate($id)
    {
        $template = KtaTemplate::findOrFail($id);

        // Nonaktifkan semua
        KtaTemplate::where('is_active', true)->update([
            'is_active' => false
        ]);

        // Aktifkan yang dipilih
        $template->update([
            'is_active' => true
        ]);

        return redirect()
            ->route('kta-templates.index')
            ->with('success', 'Template berhasil diaktifkan.');
    }
  
    public function getActive()
    {
        $template = \App\Models\KtaTemplate::where('is_active', 1)->first();

        if (!$template) {
            return response()->json([
                'success' => false,
                'message' => 'No active template found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $template->id,
                'name' => $template->name,
                'front_image' => asset('storage/' . $template->front_image),
                'back_image' => asset('storage/' . $template->back_image),
            ]
        ]);
    }
}