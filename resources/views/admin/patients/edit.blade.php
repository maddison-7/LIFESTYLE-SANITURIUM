<x-layouts.admin title="Edit Patient">
    <div class="max-w-2xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900">Edit Patient</h2>
        <p class="mt-1 text-sm text-gray-500">Update {{ $patient->name }}'s details.</p>

        <form method="POST" action="{{ route('admin.patients.update', $patient) }}" class="mt-6">
            @csrf
            @method('PUT')
            @include('admin.patients._form')
        </form>
    </div>
</x-layouts.admin>
