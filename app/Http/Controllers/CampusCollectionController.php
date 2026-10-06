<?php

namespace App\Http\Controllers;

use App\Enums\ClassSchedule;
use App\Enums\LearningMethod;
use App\Enums\ProgramType;
use App\Http\Requests\CampusFilterRequest;
use App\Services\CampusSearchService;
use App\Services\FaqService;
use App\Services\SeoPageContentService;
use Illuminate\View\View;

/**
 * Halaman SEO: daftar kampus yang sudah difilter berdasarkan jadwal, program,
 * atau metode belajar, dengan wilayah opsional.
 */
class CampusCollectionController extends Controller
{
    public function __construct(
        private readonly CampusSearchService $search,
        private readonly SeoPageContentService $content,
        private readonly FaqService $faqs,
    ) {}

    public function schedule(CampusFilterRequest $request, ClassSchedule $schedule, ?string $region = null): View
    {
        $regionName = $this->regionName($region);

        return $this->render(
            $request,
            ['schedule' => $schedule->value],
            $regionName,
            $this->content->forSchedule($schedule, $regionName),
        );
    }

    public function programType(CampusFilterRequest $request, ProgramType $programType): View
    {
        return $this->render(
            $request,
            ['program_type' => $programType->value],
            null,
            $this->content->forProgramType($programType),
        );
    }

    public function method(CampusFilterRequest $request, LearningMethod $method, ?string $region = null): View
    {
        $regionName = $this->regionName($region);

        return $this->render(
            $request,
            ['method' => $method->value],
            $regionName,
            $this->content->forMethod($method, $regionName),
        );
    }

    private function regionName(?string $slug): ?string
    {
        if ($slug === null) {
            return null;
        }

        return $this->search->resolveRegion($slug) ?? abort(404);
    }

    /**
     * @param  array<string, string>  $fixed
     * @param  array{heading: string, intro: string}  $page
     */
    private function render(CampusFilterRequest $request, array $fixed, ?string $region, array $page): View
    {
        $filters = array_merge($request->filters(), $fixed);

        if ($region !== null) {
            $filters['region'] = $region;
        }

        return view('campuses.index', $this->search->indexData(
            $filters,
            $request->term(),
            $request->sortKey(),
        ) + $page + ['faqs' => $this->faqs->forPath($request->path())]);
    }
}
