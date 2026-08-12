<x-layouts.admin title="Add Service">
    <div class="max-w-3xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900">Add New Service</h2>
        <p class="mt-1 text-sm text-gray-500">This will appear on the public Services page once saved.</p>

        <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data" class="mt-6">
            @csrf
            @include('admin.services._form')
        </form>
    </div>
</x-layouts.admin>
