<?php

namespace Database\Seeders;

use App\Enums\BrochureType;
use App\Models\Campus;
use Illuminate\Database\Seeder;

class BrochureSeeder extends Seeder
{
    public function run(): void
    {
        Campus::with('majors')->get()->each(function (Campus $campus) {
            $campus->brochures()->delete();

            $campus->brochures()->create($this->brochure($campus, 'Brosur Umum', BrochureType::General, 'umum'));
            $campus->brochures()->create($this->brochure($campus, 'Biaya Kuliah', BrochureType::Tuition, 'biaya'));

            $campus->majors->take(2)->each(fn ($major) => $campus->brochures()->create(
                $this->brochure($campus, 'Brosur '.$major->name, BrochureType::Program, $major->slug),
            ));
        });
    }

    private function brochure(Campus $campus, string $title, BrochureType $type, string $suffix): array
    {
        return [
            'title' => $title,
            'type' => $type,
            'file_path' => "brochures/{$campus->slug}-{$suffix}.pdf",
        ];
    }
}
