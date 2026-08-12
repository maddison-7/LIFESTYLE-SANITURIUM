<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Branch;
use App\Models\InventoryTransaction;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    private const STATUS_COLORS = [
        'pending' => 'bg-amber-400',
        'confirmed' => 'bg-primary-600',
        'rescheduled' => 'bg-blue-500',
        'completed' => 'bg-gray-400',
        'cancelled' => 'bg-red-500',
    ];

    public function appointments(Request $request): View|StreamedResponse
    {
        [$from, $to] = $this->resolveRange($request);

        $appointments = Appointment::query()
            ->with(['patient', 'service', 'branch'])
            ->whereBetween('appointment_date', [$from->toDateString(), $to->toDateString()])
            ->get();

        if ($request->boolean('export')) {
            return $this->csv('appointment-report-'.$from->toDateString().'-to-'.$to->toDateString(), [
                'Reference', 'Patient', 'Phone', 'Service', 'Branch', 'Date', 'Time', 'Status',
            ], $appointments->map(fn (Appointment $a) => [
                $a->reference, $a->patient->name, $a->patient->phone, $a->service->name, $a->branch->name,
                $a->appointment_date->toDateString(), $a->appointment_time, $a->statusLabel(),
            ]));
        }

        $statusBreakdown = collect(Appointment::STATUSES)->map(fn (string $status) => [
            'label' => ucfirst($status),
            'value' => $appointments->where('status', $status)->count(),
            'color' => self::STATUS_COLORS[$status],
        ])->all();

        $dailyBreakdown = $this->periodBreakdown($appointments, $from, $to);

        return view('admin.reports.appointments', [
            'from' => $from,
            'to' => $to,
            'total' => $appointments->count(),
            'statusBreakdown' => $statusBreakdown,
            'dailyBreakdown' => $dailyBreakdown,
        ]);
    }

    public function services(Request $request): View|StreamedResponse
    {
        [$from, $to] = $this->resolveRange($request);

        $counts = Appointment::query()
            ->whereBetween('appointment_date', [$from->toDateString(), $to->toDateString()])
            ->selectRaw('service_id, count(*) as total')
            ->groupBy('service_id')
            ->pluck('total', 'service_id');

        $services = Service::query()->ordered()->get()->map(fn (Service $service) => [
            'label' => $service->name,
            'value' => $counts->get($service->id, 0),
        ])->sortByDesc('value')->values()->all();

        if ($request->boolean('export')) {
            return $this->csv('service-report-'.$from->toDateString().'-to-'.$to->toDateString(), ['Service', 'Appointments'], $services);
        }

        return view('admin.reports.services', [
            'from' => $from,
            'to' => $to,
            'services' => $services,
        ]);
    }

    public function branches(Request $request): View|StreamedResponse
    {
        [$from, $to] = $this->resolveRange($request);

        $counts = Appointment::query()
            ->whereBetween('appointment_date', [$from->toDateString(), $to->toDateString()])
            ->selectRaw('branch_id, count(*) as total')
            ->groupBy('branch_id')
            ->pluck('total', 'branch_id');

        $branches = Branch::query()->ordered()->get()->map(fn (Branch $branch) => [
            'label' => $branch->name,
            'value' => $counts->get($branch->id, 0),
        ])->sortByDesc('value')->values()->all();

        if ($request->boolean('export')) {
            return $this->csv('branch-report-'.$from->toDateString().'-to-'.$to->toDateString(), ['Branch', 'Appointments'], $branches);
        }

        return view('admin.reports.branches', [
            'from' => $from,
            'to' => $to,
            'branches' => $branches,
        ]);
    }

    public function inventory(Request $request): View|StreamedResponse
    {
        [$from, $to] = $this->resolveRange($request);

        $medicines = Medicine::query()->orderBy('name')->get();

        if ($request->query('export') === 'stock') {
            return $this->csv('stock-levels-'.now()->toDateString(), ['Product', 'Category', 'Quantity', 'Status'], $medicines->map(fn (Medicine $m) => [
                $m->name, $m->category, $m->quantity, $m->statusLabel(),
            ]));
        }

        $movements = InventoryTransaction::query()
            ->whereBetween('created_at', [$from->startOfDay(), $to->endOfDay()])
            ->get();

        if ($request->query('export') === 'movement') {
            return $this->csv('stock-movement-'.$from->toDateString().'-to-'.$to->toDateString(), ['Product', 'Type', 'Quantity', 'Reference', 'Date'], $movements->load('medicine')->map(fn (InventoryTransaction $t) => [
                $t->medicine->name, $t->typeLabel(), $t->quantity, $t->reference, $t->created_at->toDateTimeString(),
            ]));
        }

        return view('admin.reports.inventory', [
            'from' => $from,
            'to' => $to,
            'medicines' => $medicines,
            'lowStock' => $medicines->filter(fn (Medicine $m) => $m->isLowStock())->values(),
            'stockIn' => $movements->where('type', InventoryTransaction::TYPE_IN)->sum('quantity'),
            'stockOut' => $movements->where('type', InventoryTransaction::TYPE_OUT)->sum('quantity'),
        ]);
    }

    public function revenue(Request $request): View|StreamedResponse
    {
        [$from, $to] = $this->resolveRange($request);

        $payments = Payment::query()
            ->paid()
            ->with(['appointment.branch'])
            ->whereBetween('paid_at', [$from, $to])
            ->get();

        if ($request->boolean('export')) {
            return $this->csv('revenue-report-'.$from->toDateString().'-to-'.$to->toDateString(), [
                'Reference', 'Patient', 'Amount', 'Method', 'Branch', 'Paid At',
            ], $payments->map(fn (Payment $p) => [
                $p->reference, $p->patient->name, $p->amount, $p->methodLabel(), $p->appointment->branch->name, $p->paid_at->toDateTimeString(),
            ]));
        }

        $byMethod = collect(Payment::METHODS)->map(fn (string $method) => [
            'label' => ucfirst(str_replace('_', ' ', $method)),
            'value' => (float) $payments->where('method', $method)->sum('amount'),
        ])->filter(fn ($row) => $row['value'] > 0)->values()->all();

        $byBranch = Branch::query()->ordered()->get()->map(fn (Branch $branch) => [
            'label' => $branch->name,
            'value' => (float) $payments->filter(fn (Payment $p) => $p->appointment->branch_id === $branch->id)->sum('amount'),
        ])->filter(fn ($row) => $row['value'] > 0)->sortByDesc('value')->values()->all();

        return view('admin.reports.revenue', [
            'from' => $from,
            'to' => $to,
            'total' => $payments->sum('amount'),
            'count' => $payments->count(),
            'byMethod' => $byMethod,
            'byBranch' => $byBranch,
        ]);
    }

    public function patientDemographics(Request $request): View|StreamedResponse
    {
        [$from, $to] = $this->resolveRange($request);

        $genderCounts = Patient::query()
            ->selectRaw('gender, count(*) as total')
            ->groupBy('gender')
            ->pluck('total', 'gender');

        $byGender = collect(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'])->map(fn ($label, $key) => [
            'label' => $label,
            'value' => (int) $genderCounts->get($key, 0),
        ])->values()->all();

        $newInRange = Patient::query()->whereBetween('created_at', [$from, $to])->count();

        return view('admin.reports.patients', [
            'from' => $from,
            'to' => $to,
            'totalPatients' => Patient::count(),
            'newInRange' => $newInRange,
            'byGender' => $byGender,
        ]);
    }

    /**
     * @return array{0: Carbon, 1: Carbon}
     */
    private function resolveRange(Request $request): array
    {
        $from = $request->filled('from') ? Carbon::parse($request->string('from')) : now()->startOfMonth();
        $to = $request->filled('to') ? Carbon::parse($request->string('to')) : now()->endOfMonth();

        if ($from->gt($to)) {
            [$from, $to] = [$to, $from];
        }

        return [$from->startOfDay(), $to->endOfDay()];
    }

    /**
     * Daily buckets for ranges up to 31 days, weekly buckets beyond that,
     * so a year-long custom range still renders as a readable bar list.
     */
    private function periodBreakdown($appointments, Carbon $from, Carbon $to): array
    {
        $useWeekly = $from->diffInDays($to) > 31;

        $grouped = $appointments->groupBy(function (Appointment $appointment) use ($useWeekly) {
            return $useWeekly
                ? $appointment->appointment_date->startOfWeek()->format('d M')
                : $appointment->appointment_date->format('d M');
        });

        return $grouped->map(fn ($group, $label) => ['label' => $label, 'value' => $group->count()])
            ->values()
            ->all();
    }

    private function csv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        $callback = function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return response()->streamDownload($callback, $filename.'.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
