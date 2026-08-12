<x-layouts.admin title="Add User">
    <div class="max-w-3xl rounded-2xl border border-surface-200 bg-white p-6 sm:p-8">
        <h2 class="text-lg font-bold text-gray-900">Add New User</h2>
        <p class="mt-1 text-sm text-gray-500">Create an account for a staff member to access the admin dashboard.</p>

        <form method="POST" action="{{ route('admin.users.store') }}" class="mt-6">
            @csrf
            @include('admin.users._form')
        </form>
    </div>
</x-layouts.admin>
