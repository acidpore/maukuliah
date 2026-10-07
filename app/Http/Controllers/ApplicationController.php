<?php

namespace App\Http\Controllers;

use App\Enums\ClassSchedule;
use App\Enums\LastEducation;
use App\Enums\ProgramType;
use App\Enums\SourceInfo;
use App\Http\Requests\StoreApplicationRequest;
use App\Models\Campus;
use App\Models\Major;
use App\Models\StudyProgram;
use App\Services\ApplicationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    public function __construct(private readonly ApplicationService $applications) {}

    public function create(Request $request): View
    {
        return view('applications.create', [
            'selectedCampusId' => $this->selectedCampusId($request->query('campus')),
            'campuses' => Campus::verified()->orderBy('name')->get(['id', 'name', 'city', 'province']),
            'majors' => Major::orderBy('name')->get(['id', 'name']),
            'campusMajors' => $this->campusMajorMap(),
            'options' => [
                'last_education' => LastEducation::cases(),
                'program_type' => ProgramType::cases(),
                'schedule' => ClassSchedule::cases(),
                'source_info' => SourceInfo::cases(),
            ],
        ]);
    }

    /**
     * Prapilih kampus dari ?campus=slug; slug tidak dikenal atau belum
     * terverifikasi diabaikan.
     */
    private function selectedCampusId(mixed $slug): ?int
    {
        if (! is_string($slug) || $slug === '') {
            return null;
        }

        return Campus::verified()->where('slug', $slug)->value('id');
    }

    /**
     * Peta kampus ke jurusan yang disediakan, dipakai klien untuk menyaring
     * pilihan jurusan sebelum formulir dikirim.
     *
     * @return array<int, array<int, int>>
     */
    private function campusMajorMap(): array
    {
        return StudyProgram::query()
            ->get(['campus_id', 'major_id'])
            ->groupBy('campus_id')
            ->map(fn ($programs) => $programs->pluck('major_id')->unique()->values()->all())
            ->all();
    }

    public function store(StoreApplicationRequest $request): RedirectResponse
    {
        $this->applications->submit(
            $request->validated(),
            $request->cookie(config('affiliate.cookie.name')),
        );

        return redirect()
            ->route('applications.create')
            ->with('status', 'Pendaftaran diterima. Tim kami akan menghubungi kamu lewat WhatsApp.');
    }
}
