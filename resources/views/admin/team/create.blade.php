<x-layouts.admin title="Add Team Member">
    <div class="max-w-3xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900">Add Healthcare Team Member</h2>
        <p class="mt-1 text-sm text-gray-500">This will appear on the public Healthcare Team page once saved.</p>

        <form method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data" class="mt-6">
            @csrf
            @include('admin.team._form')
        </form>
    </div>
</x-layouts.admin>
