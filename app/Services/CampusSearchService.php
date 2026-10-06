<?php

namespace App\Services;

use App\Enums\ClassSchedule;
use App\Enums\DegreeLevel;
use App\Enums\LearningMethod;
use App\Enums\ProgramType;
use App\Models\Campus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CampusSearchService
{
    public const PER_PAGE = 9;

    public const SORT_CHEAPEST = 'cheapest';

    private const PROGRAM_FILTER_KEYS = [
        'program_type',
        'schedule',
        'method',
        'degree_level',
        'fee_min',
        'fee_max',
    ];

    /**
     * @param  array<string, mixed>  $filters
     */
    public function search(array $filters, ?string $term, ?string $sort): LengthAwarePaginator
    {
        $query = Campus::query()
            ->verified()
            ->withCount('majors')
            ->with('studyPrograms')
            ->withMin('studyPrograms as min_monthly_installment', 'monthly_installment')
            ->search($term)
            ->filter($filters);

        $this->applyRegion($query, $filters['region'] ?? null);
        $this->applyProgramFilters($query, $filters);

        return $this->applySort($query, $sort)
            ->paginate(self::PER_PAGE)
            ->withQueryString();
    }

    /**
     * Seluruh variabel yang dibutuhkan view daftar kampus.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public function indexData(array $filters, ?string $term, ?string $sort): array
    {
        return [
            'campuses' => $this->search($filters, $term, $sort),
            'filters' => $filters,
            'query' => $term,
            'sort' => $sort,
            'forms' => $this->facet('form'),
            'cities' => $this->facet('city'),
            'provinces' => $this->facet('province'),
            'accreditations' => $this->facet('accreditation'),
            'typeOptions' => Campus::TYPE_LABELS,
            'formLabels' => Campus::FORM_LABELS,
            'programTypes' => ProgramType::cases(),
            'schedules' => ClassSchedule::cases(),
            'methods' => LearningMethod::cases(),
            'degreeLevels' => DegreeLevel::cases(),
        ];
    }

    /**
     * Mengubah slug wilayah pada URL menjadi nama provinsi atau kota yang ada.
     */
    public function resolveRegion(string $slug): ?string
    {
        $names = Campus::query()->verified()->pluck('province')
            ->merge(Campus::query()->verified()->pluck('city'))
            ->unique();

        return $names->first(fn (string $name) => Str::slug($name) === $slug);
    }

    private function applyRegion(Builder $query, ?string $region): void
    {
        if (blank($region)) {
            return;
        }

        $query->where(function (Builder $query) use ($region) {
            $query->where('province', $region)->orWhere('city', $region);
        });
    }

    /**
     * Semua syarat program harus terpenuhi oleh baris program studi yang sama.
     *
     * @param  array<string, mixed>  $filters
     */
    private function applyProgramFilters(Builder $query, array $filters): void
    {
        $active = array_intersect_key($filters, array_flip(self::PROGRAM_FILTER_KEYS));

        if ($active === []) {
            return;
        }

        $query->whereHas('studyPrograms', function (Builder $programs) use ($active) {
            foreach ($active as $key => $value) {
                $this->applyProgramFilter($programs, $key, $value);
            }
        });
    }

    private function applyProgramFilter(Builder $programs, string $key, mixed $value): void
    {
        match ($key) {
            'program_type', 'degree_level' => $programs->where($key, $value),
            'schedule' => $programs->whereJsonContains('schedules', $value),
            'method' => $programs->whereJsonContains('methods', $value),
            'fee_min' => $programs->where('monthly_installment', '>=', (int) $value),
            'fee_max' => $programs->where('monthly_installment', '<=', (int) $value),
        };
    }

    private function applySort(Builder $query, ?string $sort): Builder
    {
        if ($sort === self::SORT_CHEAPEST) {
            return $query->orderByRaw('min_monthly_installment is null')
                ->orderBy('min_monthly_installment')
                ->orderBy('name');
        }

        return $query->sorted($sort);
    }

    /**
     * @return Collection<int, string>
     */
    private function facet(string $column)
    {
        return Campus::query()->verified()->distinct()->orderBy($column)->pluck($column);
    }
}
