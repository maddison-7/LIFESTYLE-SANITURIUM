<x-layouts.admin title="Add Medicine">
    <div class="max-w-3xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900">Add New Medicine</h2>
        <p class="mt-1 text-sm text-gray-500">Add a medicine or health product to the pharmacy catalogue.</p>

        <form method="POST" action="{{ route('admin.medicines.store') }}" class="mt-6">
            @csrf
            @include('admin.medicines._form')
        </form>
    </div>
</x-layouts.admin>
