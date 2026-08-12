<?php

namespace App\Http\Controllers\Portal\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\RegisterPatientRequest;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredPatientController extends Controller
{
    public function create(): View
    {
        return view('portal.auth.register');
    }

    /**
     * A patient record may already exist from a public appointment booking
     * (created by phone number, no password). Registering with that same
     * phone "claims" the existing record instead of creating a duplicate,
     * so their appointment history is there from the moment they log in.
     */
    public function store(RegisterPatientRequest $request): RedirectResponse
    {
        $existing = Patient::where('phone', $request->validated('phone'))->first();

        if ($existing && $existing->hasPortalAccount()) {
            return back()->withErrors([
                'phone' => 'An account already exists for this phone number. Please log in instead.',
            ])->onlyInput('name', 'phone', 'email', 'gender');
        }

        $attributes = [
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'gender' => $request->validated('gender'),
            'password' => Hash::make($request->validated('password')),
        ];

        $patient = $existing
            ? tap($existing)->update($attributes)
            : Patient::create([...$attributes, 'phone' => $request->validated('phone')]);

        Auth::guard('patient')->login($patient);

        return redirect()->route('portal.dashboard')->with('success', 'Welcome! Your account is ready.');
    }
}
