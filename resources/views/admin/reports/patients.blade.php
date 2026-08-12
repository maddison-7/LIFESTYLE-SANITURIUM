<x-layouts.admin title="Patient Reports">
    @include('admin.reports._tabs')
    @include('admin.reports._date_filter', ['route' => 'admin.reports.patients', 'showExport' => false])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <p class="text-sm font-medium text-gray-500">Total Patients</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalPatients }}</p>
        </div>

        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <p class="text-sm font-medium text-gray-500">New Registrations</p>
            <p class="mt-2 text-3xl font-bold text-primary-700">{{ $newInRange }}</p>
            <p class="mt-1 text-xs text-gray-400">{{ $from->format('d M Y') }} &ndash; {{ $to->format('d M Y') }}</p>
        </div>

        <div class="rounded-2xl border border-surface-200 bg-white p-6">
            <h2 class="text-sm font-bold text-gray-900 mb-4">By Gender</h2>
            <x-admin.bar-list :items="$byGender" empty-message="No patients registered yet." />
        </div>
    </div>
</x-layouts.admin>
