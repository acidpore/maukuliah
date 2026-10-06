<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchTermRequest;
use App\Models\Campus;
use App\Models\Career;
use App\Services\FavoriteService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CareerController extends Controller
{
    private const PER_PAGE = 12;

    private const RELATED_CAMPUS_LIMIT = 8;

    public function index(SearchTermRequest $request): View
    {
        $query = $request->term();

        $careers = Career::query()
            ->search($query)
            ->orderBy('name')
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        return view('careers.index', compact('careers', 'query'));
    }

    public function show(Request $request, Career $career, FavoriteService $favorites): View
    {
        $isFavorited = $favorites->isFavoritedBy($request->user(), $career);

        $career->load(['majors' => fn ($query) => $query->orderBy('name')]);

        $relatedCampuses = Campus::query()
            ->verified()
            ->withCount('majors')
            ->whereHas('majors', fn ($query) => $query->whereIn('majors.id', $career->majors->pluck('id')))
            ->orderBy('name')
            ->limit(self::RELATED_CAMPUS_LIMIT)
            ->get();

        return view('careers.show', compact('career', 'relatedCampuses', 'isFavorited'));
    }
}
