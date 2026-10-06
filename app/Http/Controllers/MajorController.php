<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Services\FavoriteService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MajorController extends Controller
{
    public function index(Request $request): View
    {
        $query = $request->query('q');
        $category = $request->query('category');

        $majors = Major::query()
            ->withCount('campuses')
            ->search($query)
            ->filter($category)
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Major::distinct()->orderBy('category')->pluck('category');

        return view('majors.index', compact('majors', 'categories', 'query', 'category'));
    }

    public function show(Request $request, Major $major, FavoriteService $favorites): View
    {
        $isFavorited = $favorites->isFavoritedBy($request->user(), $major);

        $major->load(['careers' => fn ($query) => $query->orderBy('name')]);

        $campuses = $major->campuses()
            ->verified()
            ->withCount('majors')
            ->orderBy('name')
            ->get();

        return view('majors.show', compact('major', 'campuses', 'isFavorited'));
    }
}
