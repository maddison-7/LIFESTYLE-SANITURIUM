<x-layouts.admin title="Add Article">
    <div class="max-w-3xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900">Add Health Article</h2>
        <p class="mt-1 text-sm text-gray-500">Published articles appear on the public Health Education page.</p>

        <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data" class="mt-6">
            @csrf
            @include('admin.articles._form')
        </form>
    </div>
</x-layouts.admin>
