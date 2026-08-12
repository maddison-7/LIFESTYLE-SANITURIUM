<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');

        $services = Service::active()
            ->ordered()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->get();

        $categories = Service::active()->ordered()->pluck('category')->unique()->values();

        return view('services.index', [
            'services' => $services,
            'categories' => $categories,
            'activeCategory' => $category,
        ]);
    }
}
