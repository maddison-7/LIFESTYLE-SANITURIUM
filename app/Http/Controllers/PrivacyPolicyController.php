<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PrivacyPolicyController extends Controller
{
    public function __invoke(): View
    {
        return view('privacy-policy');
    }
}
