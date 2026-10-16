<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudyProgramRequest;
use App\Models\Campus;
use App\Models\Major;
use App\Models\StudyProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class StudyProgramController extends Controller
{
    public function index(Request $request): View
    {
        $campus = Campus::find($request->integer('campus'));

        if ($campus) {
            Gate::authorize('update', $campus);
        }

        return view('admin.study-programs.index', [
            'campus' => $campus,
            'campuses' => Campus::orderBy('name')->get(['id', 'name']),
            'studyPrograms' => $campus
                ? $campus->studyPrograms()->with('major')->orderBy('major_id')->get()
                : collect(),
            'majors' => Major::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(StudyProgramRequest $request, Campus $campus): RedirectResponse
    {
        Gate::authorize('update', $campus);

        $campus->studyPrograms()->create($request->validated());

        return back()->with('status', 'Program studi ditambahkan.');
    }

    public function update(StudyProgramRequest $request, StudyProgram $studyProgram): RedirectResponse
    {
        Gate::authorize('update', $studyProgram->campus);

        $studyProgram->update($request->validated());

        return redirect()
            ->route('admin.study-programs.index', ['campus' => $studyProgram->campus_id])
            ->with('status', 'Program studi diperbarui.');
    }

    public function destroy(StudyProgram $studyProgram): RedirectResponse
    {
        Gate::authorize('update', $studyProgram->campus);

        $studyProgram->delete();

        return redirect()
            ->route('admin.study-programs.index', ['campus' => $studyProgram->campus_id])
            ->with('status', 'Program studi dihapus.');
    }
}
