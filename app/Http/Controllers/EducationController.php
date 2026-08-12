<?php

namespace App\Http\Controllers;

use App\Models\HealthArticle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EducationController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');

        $articles = HealthArticle::published()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->latestPublished()
            ->paginate(9)
            ->withQueryString();

        $categories = HealthArticle::published()->pluck('category')->unique()->sort()->values();

        return view('education.index', [
            'articles' => $articles,
            'categories' => $categories,
            'activeCategory' => $category,
        ]);
    }

    public function show(HealthArticle $article): View
    {
        abort_unless($article->status === HealthArticle::STATUS_PUBLISHED && $article->published_at <= now(), 404);

        $related = HealthArticle::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->latestPublished()
            ->take(3)
            ->get();

        return view('education.show', [
            'article' => $article,
            'related' => $related,
        ]);
    }
}
