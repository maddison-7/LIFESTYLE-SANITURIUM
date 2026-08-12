<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\HealthArticle;
use App\Models\Service;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'services' => Service::active()->ordered()->get(),
            'branches' => Branch::visible()->ordered()->get(),
            'articles' => HealthArticle::published()->latestPublished()->take(3)->get(),
        ]);
    }
}
