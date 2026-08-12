<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHealthArticleRequest;
use App\Http\Requests\Admin\UpdateHealthArticleRequest;
use App\Models\HealthArticle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HealthArticleController extends Controller
{
    public function index(Request $request): View
    {
        $articles = HealthArticle::query()
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', '%'.$request->string('search').'%'))
            ->when($request->filled('category'), fn ($query) => $query->where('category', $request->string('category')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.articles.index', [
            'articles' => $articles,
            'categories' => HealthArticle::CATEGORIES,
            'filters' => $request->only(['search', 'category', 'status']),
        ]);
    }

    public function create(): View
    {
        return view('admin.articles.create', ['article' => null]);
    }

    public function store(StoreHealthArticleRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('featured_image');
        $data['published_at'] = $this->resolvePublishedAt($request->validated('status'), $request->validated('published_at'));

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        HealthArticle::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully.');
    }

    public function edit(HealthArticle $article): View
    {
        return view('admin.articles.edit', ['article' => $article]);
    }

    public function update(UpdateHealthArticleRequest $request, HealthArticle $article): RedirectResponse
    {
        $data = $request->safe()->except('featured_image');
        $data['published_at'] = $this->resolvePublishedAt($request->validated('status'), $request->validated('published_at'), $article);

        if ($request->hasFile('featured_image')) {
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('articles', 'public');
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(HealthArticle $article): RedirectResponse
    {
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted.');
    }

    /**
     * Publishing without an explicit date stamps it now. Saving as a draft
     * clears any published_at so it can't leak onto the public site.
     */
    private function resolvePublishedAt(string $status, ?string $publishedAt, ?HealthArticle $article = null): ?string
    {
        if ($status !== HealthArticle::STATUS_PUBLISHED) {
            return null;
        }

        return $publishedAt ?? $article?->published_at ?? now();
    }
}
