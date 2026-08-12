<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePatientRequest;
use App\Http\Requests\Admin\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $patients = Patient::query()
            ->withCount('appointments')
            ->search($request->query('search'))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.patients.index', [
            'patients' => $patients,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create(): View
    {
        return view('admin.patients.create', ['patient' => null]);
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        $patient = Patient::create($request->validated());

        return redirect()->route('admin.patients.show', $patient)->with('success', 'Patient added successfully.');
    }

    public function show(Patient $patient): View
    {
        $patient->load(['appointments' => function ($query) {
            $query->with(['service', 'branch'])->orderByDesc('appointment_date')->orderByDesc('appointment_time');
        }]);

        return view('admin.patients.show', ['patient' => $patient]);
    }

    public function edit(Patient $patient): View
    {
        return view('admin.patients.edit', ['patient' => $patient]);
    }

    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        $patient->update($request->validated());

        return redirect()->route('admin.patients.show', $patient)->with('success', 'Patient details updated successfully.');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        if ($patient->appointments()->exists()) {
            return back()->with('error', 'This patient has appointment history and cannot be deleted.');
        }

        $patient->delete();

        return redirect()->route('admin.patients.index')->with('success', 'Patient deleted.');
    }
}
