<x-layouts.admin title="Add Branch">
    <div class="max-w-3xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900">Add New Branch</h2>
        <p class="mt-1 text-sm text-gray-500">This will appear on the public Branches page once saved.</p>

        <form method="POST" action="{{ route('admin.branches.store') }}" class="mt-6">
            @csrf
            @include('admin.branches._form')
        </form>
    </div>
</x-layouts.admin>
