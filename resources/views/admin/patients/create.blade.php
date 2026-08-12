<x-layouts.admin title="Add Patient">
    <div class="max-w-2xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900">Add New Patient</h2>
        <p class="mt-1 text-sm text-gray-500">Register a patient who is not yet in the system, e.g. a walk-in visit.</p>

        <form method="POST" action="{{ route('admin.patients.store') }}" class="mt-6">
            @csrf
            @include('admin.patients._form')
        </form>
    </div>
</x-layouts.admin>
