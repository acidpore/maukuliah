<?php

namespace App\Http\Controllers;

use App\Models\Major;
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

    public function show(Major $major): View
    {
        $campuses = $major->campuses()
            ->withCount('majors')
            ->orderBy('name')
            ->get();

        return view('majors.show', compact('major', 'campuses'));
    }
}
