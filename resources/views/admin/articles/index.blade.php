<x-layouts.admin title="Health Articles">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <p class="text-sm text-gray-500">Manage articles shown on the public Health Education page.</p>
        <x-btn :href="route('admin.articles.create')" variant="primary" size="sm">
            Add Article
        </x-btn>
    </div>

    <form method="GET" action="{{ route('admin.articles.index') }}" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
        <input
            type="text"
            name="search"
            value="{{ $filters['search'] ?? '' }}"
            placeholder="Search by title..."
            class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
        >
        <select name="category" onchange="this.form.submit()" class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm bg-white">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(($filters['category'] ?? '') === $category)>{{ $category }}</option>
            @endforeach
        </select>
        <select name="status" onchange="this.form.submit()" class="w-full rounded-lg border border-surface-300 px-3.5 py-2.5 text-sm bg-white">
            <option value="">All Statuses</option>
            <option value="draft" @selected(($filters['status'] ?? '') === 'draft')>Draft</option>
            <option value="published" @selected(($filters['status'] ?? '') === 'published')>Published</option>
        </select>
    </form>

    <div class="rounded-2xl border border-surface-200 bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-surface-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="px-5 py-3">Title</th>
                        <th class="px-5 py-3">Category</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3">Published</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @forelse ($articles as $article)
                        <tr>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($article->featured_image)
                                        <img src="{{ $article->featuredImageUrl() }}" alt="{{ $article->title }}" class="h-10 w-14 rounded-lg object-cover">
                                    @endif
                                    <span class="font-medium text-gray-900">{{ $article->title }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $article->category }}</td>
                            <td class="px-5 py-4">
                                <x-badge :color="$article->status === 'published' ? 'primary' : 'gray'">{{ $article->statusLabel() }}</x-badge>
                            </td>
                            <td class="px-5 py-4 text-gray-500">{{ $article->published_at?->format('d M Y') ?: '—' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="text-sm font-semibold text-primary-700 hover:text-primary-800">
                                        Edit
                                    </a>
                                    <x-confirm-form
                                        :action="route('admin.articles.destroy', $article)"
                                        title="Delete this article?"
                                        :message="'This will permanently remove &quot;' . $article->title . '&quot; from the public Health Education page.'"
                                    />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-gray-500">No articles found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($articles->hasPages())
            <div class="border-t border-surface-100 px-5 py-4">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
