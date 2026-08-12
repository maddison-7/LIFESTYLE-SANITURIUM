<x-layouts.admin title="Edit Service">
    <div class="max-w-3xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900">Edit Service</h2>
        <p class="mt-1 text-sm text-gray-500">Update {{ $service->name }}.</p>

        <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data" class="mt-6">
            @csrf
            @method('PUT')
            @include('admin.services._form')
        </form>
    </div>
</x-layouts.admin>
