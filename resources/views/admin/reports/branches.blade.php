<x-layouts.admin title="Branch Reports">
    @include('admin.reports._tabs')
    @include('admin.reports._date_filter', ['route' => 'admin.reports.branches'])

    <div class="rounded-2xl border border-surface-200 bg-white p-6">
        <h2 class="text-sm font-bold text-gray-900 mb-1">Appointments by Branch</h2>
        <p class="text-xs text-gray-500 mb-4">{{ $from->format('d M Y') }} &ndash; {{ $to->format('d M Y') }}</p>
        <x-admin.bar-list :items="$branches" empty-message="No appointments in this date range." />
    </div>
</x-layouts.admin>
