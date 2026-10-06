<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampusFilterRequest;
use App\Models\Campus;
use App\Services\CampusDetailService;
use App\Services\CampusSearchService;
use App\Services\FavoriteService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampusController extends Controller
{
    public function __construct(
        private readonly CampusSearchService $search,
        private readonly CampusDetailService $detail,
        private readonly FavoriteService $favorites,
    ) {}

    public function index(CampusFilterRequest $request): View
    {
        return view('campuses.index', $this->search->indexData(
            $request->filters(),
            $request->term(),
            $request->sortKey(),
        ));
    }

    public function show(Request $request, Campus $campus): View
    {
        abort_unless($campus->isVerified(), 404);

        return view('campuses.show', $this->detail->data($campus) + [
            'isFavorited' => $this->favorites->isFavoritedBy($request->user(), $campus),
        ]);
    }
}
