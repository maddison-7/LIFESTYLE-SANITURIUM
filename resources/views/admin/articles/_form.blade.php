@php
    $statusOptions = ['draft' => 'Draft', 'published' => 'Published'];
    $categoryOptions = array_combine(\App\Models\HealthArticle::CATEGORIES, \App\Models\HealthArticle::CATEGORIES);
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
        <x-form.input label="Title" name="title" :value="$article->title ?? ''" :required="true" />
    </div>

    <x-form.select label="Category" name="category" :options="$categoryOptions" placeholder="Select a category" :value="$article->category ?? ''" :required="true" />
    <x-form.select label="Status" name="status" :options="$statusOptions" :value="$article->status ?? 'draft'" :required="true" />

    <div class="sm:col-span-2">
        <x-form.textarea label="Excerpt" name="excerpt" :value="$article->excerpt ?? ''" :rows="2" hint="Short summary shown on article cards. Auto-generated from content if left blank." />
    </div>

    <div class="sm:col-span-2">
        <x-form.textarea label="Content" name="content" :value="$article->content ?? ''" :rows="12" :required="true" hint="Plain text — separate paragraphs with a blank line." />
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1.5">Featured Image</label>
        @if (!empty($article?->featured_image))
            <img src="{{ $article->featuredImageUrl() }}" alt="{{ $article->title }}" class="h-16 w-28 rounded-lg object-cover mb-3">
        @endif
        <input type="file" name="featured_image" accept="image/*" class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-700 hover:file:bg-primary-100">
        @error('featured_image') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <x-form.input
        label="Publish Date"
        name="published_at"
        type="datetime-local"
        :value="$article?->published_at?->format('Y-m-d\TH:i')"
        hint="Leave blank to publish immediately when status is set to Published."
    />
</div>

<div class="mt-8 flex items-center gap-3">
    <x-btn type="submit" variant="primary">
        {{ isset($article) ? 'Save Changes' : 'Create Article' }}
    </x-btn>
    <x-btn :href="route('admin.articles.index')" variant="ghost">
        Cancel
    </x-btn>
</div>
