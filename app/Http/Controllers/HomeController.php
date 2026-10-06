<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Career;
use App\Models\Major;
use App\Models\Scholarship;
use Illuminate\View\View;

class HomeController extends Controller
{
    private const FEATURED_CAMPUSES = 6;

    private const FEATURED_MAJORS = 8;

    private const FEATURED_CAREERS = 6;

    private const FEATURED_SCHOLARSHIPS = 3;

    public function __invoke(): View
    {
        $featuredCampuses = Campus::verified()
            ->withCount('majors')
            ->orderBy('id')
            ->limit(self::FEATURED_CAMPUSES)
            ->get();

        $featuredMajors = Major::withCount('campuses')
            ->orderByDesc('campuses_count')
            ->orderBy('name')
            ->limit(self::FEATURED_MAJORS)
            ->get();

        $featuredCareers = Career::orderBy('name')
            ->limit(self::FEATURED_CAREERS)
            ->get();

        $featuredScholarships = Scholarship::with('campus')
            ->open()
            ->upcomingFirst()
            ->limit(self::FEATURED_SCHOLARSHIPS)
            ->get();

        $stats = [
            'campuses' => Campus::verified()->count(),
            'majors' => Major::count(),
            'cities' => Campus::verified()->distinct()->count('city'),
        ];

        return view('home', compact(
            'featuredCampuses',
            'featuredMajors',
            'featuredCareers',
            'featuredScholarships',
            'stats',
        ));
    }
}
