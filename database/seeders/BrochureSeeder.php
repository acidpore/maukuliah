<?php

namespace Database\Seeders;

use App\Enums\BrochureType;
use App\Models\Campus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BrochureSeeder extends Seeder
{
    /**
     * PDF satu halaman kosong, cukup agar unduhan contoh bisa dibuka.
     */
    private const PLACEHOLDER_PDF = "%PDF-1.4\n1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 595 842]>>endobj\ntrailer<</Root 1 0 R>>\n%%EOF\n";

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
        $path = "brochures/{$campus->slug}-{$suffix}.pdf";
        Storage::put($path, self::PLACEHOLDER_PDF);

        return [
            'title' => $title,
            'type' => $type,
            'file_path' => $path,
        ];
    }
}
