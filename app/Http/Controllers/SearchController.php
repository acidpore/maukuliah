<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use App\Models\Major;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = $request->query('q');

        $campuses = Campus::query()
            ->verified()
            ->withCount('majors')
            ->search($query)
            ->orderBy('name')
            ->limit(20)
            ->get();

        $majors = Major::query()
            ->withCount('campuses')
            ->search($query)
            ->orderBy('name')
            ->limit(20)
            ->get();

        return view('search', compact('query', 'campuses', 'majors'));
    }
}
