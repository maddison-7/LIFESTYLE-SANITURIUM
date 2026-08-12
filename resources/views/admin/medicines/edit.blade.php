<x-layouts.admin title="Edit Medicine">
    <div class="max-w-3xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900">Edit Medicine</h2>
        <p class="mt-1 text-sm text-gray-500">
            Update {{ $medicine->name }}. Current stock ({{ $medicine->quantity }} units) is managed from the
            <a href="{{ route('admin.medicines.show', $medicine) }}" class="text-primary-700 font-semibold hover:text-primary-800">product page</a>.
        </p>

        <form method="POST" action="{{ route('admin.medicines.update', $medicine) }}" class="mt-6">
            @csrf
            @method('PUT')
            @include('admin.medicines._form')
        </form>
    </div>
</x-layouts.admin>
