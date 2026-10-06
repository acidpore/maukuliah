<?php

namespace App\Services;

use App\Enums\ClassSchedule;
use App\Enums\LearningMethod;
use App\Enums\ProgramType;

class SeoPageContentService
{
    /**
     * @return array{heading: string, intro: string}
     */
    public function forSchedule(ClassSchedule $schedule, ?string $region): array
    {
        $place = $this->placeSuffix($region);

        return [
            'heading' => "Kuliah {$schedule->label()}{$place}",
            'intro' => "Daftar kampus yang membuka kelas {$schedule->label()}{$place}. "
                .'Bandingkan biaya per bulan, program, dan metode belajar sesuai waktu luangmu.',
        ];
    }

    /**
     * @return array{heading: string, intro: string}
     */
    public function forMethod(LearningMethod $method, ?string $region): array
    {
        $place = $this->placeSuffix($region);

        return [
            'heading' => "Kuliah {$method->label()}{$place}",
            'intro' => "{$method->description()} Temukan kampus yang menyediakan metode ini{$place}.",
        ];
    }

    /**
     * @return array{heading: string, intro: string}
     */
    public function forProgramType(ProgramType $type): array
    {
        return [
            'heading' => $type->label(),
            'intro' => $type->description().' Pilih kampus dan jurusan yang menyediakan program ini.',
        ];
    }

    private function placeSuffix(?string $region): string
    {
        return $region === null ? '' : " di {$region}";
    }
}
