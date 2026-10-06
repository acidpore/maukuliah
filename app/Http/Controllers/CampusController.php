<?php

namespace App\Http\Controllers;

use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampusController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->only(Campus::FILTERABLE_COLUMNS);
        $query = $request->query('q');
        $sort = $request->query('sort');

        $campuses = Campus::query()
            ->withCount('majors')
            ->search($query)
            ->filter($filters)
            ->sorted($sort)
            ->paginate(9)
            ->withQueryString();

        return view('campuses.index', [
            'campuses' => $campuses,
            'filters' => $filters,
            'query' => $query,
            'sort' => $sort,
            'forms' => Campus::distinct()->orderBy('form')->pluck('form'),
            'cities' => Campus::distinct()->orderBy('city')->pluck('city'),
            'provinces' => Campus::distinct()->orderBy('province')->pluck('province'),
            'accreditations' => Campus::distinct()->orderBy('accreditation')->pluck('accreditation'),
            'typeOptions' => Campus::TYPE_LABELS,
            'formLabels' => Campus::FORM_LABELS,
        ]);
    }

    public function show(Campus $campus): View
    {
        $campus->load([
            'majors' => fn ($query) => $query->orderBy('name'),
        ]);

        return view('campuses.show', compact('campus'));
    }
}
