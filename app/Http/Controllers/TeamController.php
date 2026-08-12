<?php

namespace App\Http\Controllers;

use App\Models\HealthcareStaff;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        return view('team.index', [
            'staff' => HealthcareStaff::active()->ordered()->get(),
        ]);
    }
}
