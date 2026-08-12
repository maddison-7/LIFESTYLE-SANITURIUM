<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\UpdatePatientProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('portal.profile', ['patient' => Auth::guard('patient')->user()]);
    }

    /**
     * Phone number is deliberately not editable here — it's the identifier
     * that ties public bookings back to this account (see
     * AppointmentController::store's find-or-create-by-phone logic).
     */
    public function update(UpdatePatientProfileRequest $request): RedirectResponse
    {
        $patient = Auth::guard('patient')->user();

        $patient->fill($request->safe()->except('password'));

        if ($request->filled('password')) {
            $patient->password = Hash::make($request->validated('password'));
        }

        $patient->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
