<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        if (! array_key_exists($locale, config('localization.available_locales'))) {
            abort(404);
        }

        $request->session()->put('locale', $locale);

        return back();
    }
}
