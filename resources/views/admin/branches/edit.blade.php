<x-layouts.admin title="Edit Branch">
    <div class="max-w-3xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900">Edit Branch</h2>
        <p class="mt-1 text-sm text-gray-500">Update {{ $branch->name }}.</p>

        <form method="POST" action="{{ route('admin.branches.update', $branch) }}" class="mt-6">
            @csrf
            @method('PUT')
            @include('admin.branches._form')
        </form>
    </div>
</x-layouts.admin>
