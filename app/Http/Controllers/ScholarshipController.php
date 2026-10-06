<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchTermRequest;
use App\Models\Scholarship;
use App\Services\FavoriteService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScholarshipController extends Controller
{
    private const PER_PAGE = 12;

    public function index(SearchTermRequest $request): View
    {
        $query = $request->term();

        $scholarships = Scholarship::query()
            ->with('campus')
            ->search($query)
            ->orderBy('end_date')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('scholarships.index', compact('scholarships', 'query'));
    }

    public function show(Request $request, Scholarship $scholarship, FavoriteService $favorites): View
    {
        $scholarship->load('campus');
        $isFavorited = $favorites->isFavoritedBy($request->user(), $scholarship);

        return view('scholarships.show', compact('scholarship', 'isFavorited'));
    }
}
