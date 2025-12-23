<?php

namespace App\Http\Controllers;

use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NewsArticleController extends Controller
{
    public function index()
    {
        $articles = NewsArticle::orderBy('id', 'desc')->paginate(10);

        return view('pages.master.news.index', compact('articles'));
    }

    public function create()
    {
        return view('pages.master.news.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'category' => 'required|string',
            'content' => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'video_url' => 'nullable|string|max:255',
        ]);

        // Folder
        if (!Storage::exists('public/news')) {
            Storage::makeDirectory('public/news');
        }

        if ($request->hasFile('cover_image')) {
            $filename = time() . '_' . uniqid() . '.' . $request->file('cover_image')->extension();
            $request->file('cover_image')->storeAs('public/news', $filename);
            $validated['cover_image'] = $filename;
        }
        $validated['video_url'] = $request->video_url;


        $validated['author_id'] = Auth::id();

        NewsArticle::create($validated);

        return redirect()->route('news.index')->with('success', 'Artikel berhasil ditambahkan');
    }

    public function edit($id)
    {
        $article = NewsArticle::findOrFail($id);
        return view('pages.master.news.edit', compact('article'));
    }

    public function update(Request $request, $id)
    {
        $article = NewsArticle::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:berita,pengumuman,lainnya',
            'content' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'video_url' => 'nullable|string|max:255',
        ]);

        $validated['video_url'] = $request->video_url;
        if ($request->hasFile('cover_image')) {

            if ($article->cover_image && Storage::exists('public/news/' . $article->cover_image)) {
                Storage::delete('public/news/' . $article->cover_image);
            }

            $filename = time() . '_' . uniqid() . '.' . $request->file('cover_image')->extension();
            $request->file('cover_image')->storeAs('public/news', $filename);

            $validated['cover_image'] = $filename;
        }

        $article->update($validated);

        return redirect()->route('news.index')->with('success', 'Artikel berhasil diperbarui');
    }

    public function destroy($id)
    {
        $article = NewsArticle::findOrFail($id);

        if ($article->cover_image && Storage::exists('public/news/' . $article->cover_image)) {
            Storage::delete('public/news/' . $article->cover_image);
        }

        $article->delete();

        return redirect()->route('news.index')->with('success', 'Artikel berhasil dihapus');
    }

    public function show($id)
    {
        $article = NewsArticle::with('author')->findOrFail($id);

        $article->cover_url = $article->cover_image
            ? asset('storage/news/' . $article->cover_image)
            : null;

        return view('pages.master.news.show', compact('article'));
    }
}
