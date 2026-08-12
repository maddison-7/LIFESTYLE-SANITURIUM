<x-layouts.admin title="Dashboard">
    <div class="mb-6">
        <p class="text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}. Here's an overview of the clinic.</p>
        @if ($branchScoped)
            <p class="mt-1 text-xs font-semibold text-primary-700">Appointment figures below are scoped to {{ $branchName }}.</p>
        @endif
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.appointments.index', ['date' => today()->toDateString()]) }}">
            <x-admin.stat-card
                label="Appointments Today"
                :value="$stats['appointments_today']"
                icon="M6.75 3v2.25M17.25 3v2.25M3.75 8.25h16.5M5.25 5.25h13.5a1.5 1.5 0 0 1 1.5 1.5v12a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-12a1.5 1.5 0 0 1 1.5-1.5Z"
            />
        </a>
        <a href="{{ route('admin.appointments.index', ['status' => 'pending']) }}">
            <x-admin.stat-card
                label="Pending Appointments"
                :value="$stats['pending_appointments']"
                icon="M12 6v6l4 2|M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
            />
        </a>
        <a href="{{ route('admin.appointments.index', ['status' => 'confirmed']) }}">
            <x-admin.stat-card
                label="Confirmed Appointments"
                :value="$stats['confirmed_appointments']"
                icon="M9 12.75 11.25 15 15 9.75|M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
            />
        </a>
        <a href="{{ route('admin.appointments.index', ['status' => 'completed']) }}">
            <x-admin.stat-card
                label="Completed Appointments"
                :value="$stats['completed_appointments']"
                icon="M4.5 12.75l6 6 9-13.5"
            />
        </a>
        <a href="{{ route('admin.patients.index') }}">
            <x-admin.stat-card
                label="Total Patients / Clients"
                :value="$stats['total_patients']"
                icon="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Z"
            />
        </a>
        @can('manage-settings')
            <a href="{{ route('admin.services.index') }}">
                <x-admin.stat-card
                    label="Total Services"
                    :value="$stats['total_services']"
                    icon="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.623 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"
                />
            </a>
            <a href="{{ route('admin.team.index') }}">
                <x-admin.stat-card
                    label="Healthcare Staff"
                    :value="$stats['healthcare_staff']"
                    icon="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                />
            </a>
            <a href="{{ route('admin.articles.index') }}">
                <x-admin.stat-card
                    label="Health Articles"
                    :value="$stats['health_articles']"
                    icon="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"
                />
            </a>
        @else
            <x-admin.stat-card
                label="Total Services"
                :value="$stats['total_services']"
                icon="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.623 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"
            />
            <x-admin.stat-card
                label="Healthcare Staff"
                :value="$stats['healthcare_staff']"
                icon="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
            />
            <x-admin.stat-card
                label="Health Articles"
                :value="$stats['health_articles']"
                icon="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"
            />
        @endcan
    </div>

    <div class="mt-8 rounded-2xl border border-dashed border-surface-300 bg-white p-10 text-center">
        <p class="text-gray-600">Appointment charts (by month, service and branch) will appear here once the Reports module ships in Phase 7.</p>
    </div>
</x-layouts.admin>
