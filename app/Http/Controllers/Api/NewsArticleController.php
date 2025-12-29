<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class NewsArticleController extends Controller
{
    /**
     * LIST ARTIKEL (Mobile)
     */
public function index(Request $request)
{
    $baseQuery = NewsArticle::with('author:id,name')
        ->latest();

    if ($request->filled('category')) {
        $baseQuery->where('category', $request->category);
    }

    if ($request->has('search')) {
        $search = trim($request->search);

        if ($search !== '') {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('author', function ($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }
    }

    $featured = (clone $baseQuery)
        ->take(3)
        ->get();

    $featuredIds = $featured->pluck('id');

    $articles = (clone $baseQuery)
        ->whereNotIn('id', $featuredIds)
        ->paginate(10);

    return response()->json([
        'success' => true,
        'data' => [
            'featured' => $featured->map(fn($a) => $this->formatArticle($a)),
            'articles' => $articles->through(fn($a) => $this->formatArticle($a)),
        ],
    ]);
}



    /**
     * DETAIL ARTIKEL
     */
    public function show($id)
    {
        $article = NewsArticle::with('author:id,name')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $this->formatArticle($article, true),
        ]);
    }

    /**
     * FORMAT RESPONSE
     */
    protected function formatArticle(NewsArticle $article, $full = false)
    {
        return [
            'id' => $article->id,
            'title' => $article->title,
            'category' => $article->category,
            'excerpt' => $full ? null : \Str::limit(strip_tags($article->content), 120),
            'content' => $full ? $article->content : null,
            'cover_image' => $article->cover_url,
            'video_url' => $article->video_url,
            'author' => $article->author?->name,
            'published_at' => $article->created_at->format('d M Y'),
        ];
    }
}
