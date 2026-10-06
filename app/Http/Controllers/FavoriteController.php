<?php

namespace App\Http\Controllers;

use App\Http\Requests\ToggleFavoriteRequest;
use App\Services\FavoriteService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function __construct(private readonly FavoriteService $favorites) {}

    public function index(Request $request): View
    {
        return view('favorites.index', [
            'favorites' => $this->favorites->groupedFor($request->user()),
        ]);
    }

    public function toggle(ToggleFavoriteRequest $request): RedirectResponse
    {
        $item = $this->favorites->resolve($request->input('type'), (int) $request->input('id'));

        $this->favorites->toggle($request->user(), $item);

        return back();
    }
}
