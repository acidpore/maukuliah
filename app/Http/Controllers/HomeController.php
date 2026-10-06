<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Major;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredCampuses = Campus::withCount('majors')
            ->orderBy('id')
            ->limit(6)
            ->get();

        $featuredMajors = Major::withCount('campuses')
            ->orderByDesc('campuses_count')
            ->orderBy('name')
            ->limit(8)
            ->get();

        $stats = [
            'campuses' => Campus::count(),
            'majors' => Major::count(),
            'cities' => Campus::distinct()->count('city'),
        ];

        return view('home', compact('featuredCampuses', 'featuredMajors', 'stats'));
    }
}
