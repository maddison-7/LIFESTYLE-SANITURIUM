@php
    $tabs = [
        'admin.reports.appointments' => 'Appointments',
        'admin.reports.services' => 'Services',
        'admin.reports.branches' => 'Branches',
        'admin.reports.revenue' => 'Revenue',
        'admin.reports.patients' => 'Patients',
        'admin.reports.inventory' => 'Inventory',
    ];
@endphp

<div class="flex flex-wrap gap-2 mb-6">
    @foreach ($tabs as $routeName => $label)
        <a
            href="{{ route($routeName) }}"
            class="px-4 py-2 rounded-lg text-sm font-semibold transition-base {{ request()->routeIs($routeName) ? 'bg-primary-600 text-white' : 'bg-white border border-surface-200 text-gray-700 hover:bg-surface-50' }}"
        >
            {{ $label }}
        </a>
    @endforeach
</div>
