<?php

namespace App\Services;

use App\Models\Campus;

/**
 * Menyiapkan data halaman detail kampus agar controller tetap tipis.
 */
class CampusDetailService
{
    public function __construct(private readonly FaqService $faqs) {}

    /**
     * @return array<string, mixed>
     */
    public function data(Campus $campus): array
    {
        $campus->load([
            'majors' => fn ($query) => $query->orderBy('name'),
            'admissionPeriods' => fn ($query) => $query->orderBy('opens_at'),
        ]);

        return [
            'campus' => $campus,
            'brochures' => $campus->brochures()->orderBy('type')->orderBy('title')->get(),
            'admissionPeriods' => $campus->admissionPeriods,
            'studyPrograms' => $campus->studyPrograms()
                ->with('major')
                ->orderBy('degree_level')
                ->orderBy('monthly_installment')
                ->get(),
            'testimonials' => $campus->testimonials()->orderBy('id')->get(),
            'photos' => $campus->photos,
            'faqs' => $this->faqs->forPath('universities/'.$campus->slug),
        ];
    }
}
