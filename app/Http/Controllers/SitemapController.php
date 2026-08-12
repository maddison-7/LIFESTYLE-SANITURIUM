<?php

namespace App\Http\Controllers;

use App\Models\HealthArticle;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $staticRoutes = [
            ['route' => 'home', 'priority' => '1.0'],
            ['route' => 'about', 'priority' => '0.7'],
            ['route' => 'services.index', 'priority' => '0.9'],
            ['route' => 'branches.index', 'priority' => '0.7'],
            ['route' => 'team.index', 'priority' => '0.6'],
            ['route' => 'education.index', 'priority' => '0.7'],
            ['route' => 'contact', 'priority' => '0.6'],
            ['route' => 'appointments.create', 'priority' => '0.9'],
            ['route' => 'privacy-policy', 'priority' => '0.3'],
        ];

        $articles = HealthArticle::published()->get(['slug', 'updated_at']);

        $xml = view('sitemap', [
            'staticRoutes' => $staticRoutes,
            'articles' => $articles,
        ])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}
