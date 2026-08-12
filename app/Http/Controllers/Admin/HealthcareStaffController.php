<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreHealthcareStaffRequest;
use App\Http\Requests\Admin\UpdateHealthcareStaffRequest;
use App\Models\HealthcareStaff;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HealthcareStaffController extends Controller
{
    public function index(): View
    {
        return view('admin.team.index', [
            'staff' => HealthcareStaff::query()->ordered()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.team.create', ['member' => null]);
    }

    public function store(StoreHealthcareStaffRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('photo');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('team', 'public');
        }

        HealthcareStaff::create($data);

        return redirect()->route('admin.team.index')->with('success', 'Team member added successfully.');
    }

    public function edit(HealthcareStaff $member): View
    {
        return view('admin.team.edit', ['member' => $member]);
    }

    public function update(UpdateHealthcareStaffRequest $request, HealthcareStaff $member): RedirectResponse
    {
        $data = $request->safe()->except('photo');

        if ($request->hasFile('photo')) {
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $data['photo'] = $request->file('photo')->store('team', 'public');
        }

        $member->update($data);

        return redirect()->route('admin.team.index')->with('success', 'Team member updated successfully.');
    }

    public function destroy(HealthcareStaff $member): RedirectResponse
    {
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()->route('admin.team.index')->with('success', 'Team member removed.');
    }
}
