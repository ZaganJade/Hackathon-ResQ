<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles.
     */
    public function index(Request $request): View
    {
        $query = Article::published()->with('author');

        // Category filter
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        // Get unique categories for filter
        $categories = Article::published()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        // Popular articles (most viewed)
        $popularArticles = Article::published()
            ->orderByDesc('view_count')
            ->limit(5)
            ->get();

        return view('articles.index', [
            'articles' => $articles,
            'categories' => $categories,
            'popularArticles' => $popularArticles,
            'currentCategory' => $request->category,
            'searchQuery' => $request->search,
        ]);
    }

    /**
     * Display the specified article.
     */
    public function show(string $slug): View
    {
        $article = Article::published()
            ->with('author')
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        $article->incrementViewCount();

        // Get related articles
        $relatedArticles = $article->related(3);

        // Get popular articles
        $popularArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->orderByDesc('view_count')
            ->limit(4)
            ->get();

        return view('articles.show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'popularArticles' => $popularArticles,
        ]);
    }

    /**
     * Display articles by category.
     */
    public function category(string $category): View
    {
        $articles = Article::published()
            ->where('category', $category)
            ->with('author')
            ->orderByDesc('published_at')
            ->paginate(12);

        $categories = Article::published()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort()
            ->values();

        return view('articles.category', [
            'articles' => $articles,
            'categories' => $categories,
            'currentCategory' => $category,
        ]);
    }
}
