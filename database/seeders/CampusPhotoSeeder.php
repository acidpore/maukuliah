<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\CampusPhoto;
use Illuminate\Database\Seeder;

/**
 * Foto kampus dari Wikimedia Commons (sumber dan lisensi di docs/referensi-foto-kampus.md).
 * Baris dibuat hanya bila berkasnya ada di public/images/campuses.
 */
class CampusPhotoSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->photos() as $slug => $items) {
            $campus = Campus::where('slug', $slug)->first();

            if ($campus === null) {
                continue;
            }

            foreach ($items as $index => [$file, $description, $credit]) {
                $path = "images/campuses/{$slug}/{$file}";

                if (! file_exists(public_path($path))) {
                    continue;
                }

                CampusPhoto::updateOrCreate(
                    ['campus_id' => $campus->id, 'path' => $path],
                    ['caption' => "{$description}. Foto: {$credit}, Wikimedia Commons", 'sort' => $index],
                );
            }
        }
    }

    /**
     * @return array<string, array<int, array{0: string, 1: string, 2: string}>>
     */
    private function photos(): array
    {
        return [
            'universitas-indonesia' => [
                ['universitas-indonesia-1.jpg', 'Perpustakaan Pusat Universitas Indonesia', 'Kaliper1, CC BY-SA 4.0'],
                ['universitas-indonesia-2.jpg', 'Danau Kenanga di kampus Depok', 'Alif Mikail, CC BY-SA 4.0'],
                ['universitas-indonesia-3.jpg', 'Gedung Rektorat Universitas Indonesia', 'Ilham Kuniawan Gumilang, CC BY-SA 4.0'],
            ],
            'universitas-gadjah-mada' => [
                ['universitas-gadjah-mada-1.jpg', 'Balairung Universitas Gadjah Mada', 'Fhikri Latifi, CC0'],
                ['universitas-gadjah-mada-2.jpg', 'Gedung Pusat Universitas Gadjah Mada', 'Risanprasetyo/Febri Ady Prasetyo, CC BY-SA 4.0'],
                ['universitas-gadjah-mada-3.jpg', 'Sisi utara Balairung UGM', 'Sam Hidayat, CC BY-SA 4.0'],
            ],
            'institut-teknologi-bandung' => [
                ['institut-teknologi-bandung-1.jpg', 'Aula Barat ITB', 'A2613, CC BY-SA 4.0'],
                ['institut-teknologi-bandung-2.jpg', 'Gerbang depan kampus Ganesha ITB', 'A2613, CC BY-SA 4.0'],
                ['institut-teknologi-bandung-3.jpg', 'Aula Timur ITB', 'A2613, CC BY-SA 4.0'],
            ],
            'universitas-airlangga' => [
                ['universitas-airlangga-1.jpg', 'Gedung Universitas Airlangga di tepi danau', 'cicityara, domain publik'],
                ['universitas-airlangga-2.jpg', 'Kampus Universitas Airlangga', 'sulos, CC BY 3.0'],
                ['universitas-airlangga-3.jpg', 'Fakultas Ilmu Budaya Universitas Airlangga', 'antonov0002, CC BY 3.0'],
            ],
            'universitas-padjadjaran' => [
                ['universitas-padjadjaran-1.jpg', 'Graha Sanusi Hardjadinata, kampus Dipati Ukur', 'Medelam, CC BY-SA 4.0'],
                ['universitas-padjadjaran-2.jpg', 'Gedung Rektorat Universitas Padjadjaran', 'Medelam, CC BY-SA 4.0'],
                ['universitas-padjadjaran-3.jpg', 'Gerbang Universitas Padjadjaran', 'Nur Cholis, CC BY-SA 3.0'],
            ],
            'institut-pertanian-bogor' => [
                ['institut-pertanian-bogor-1.jpg', 'Taman utama Andi Hakim Nasution', 'Hysocc, CC BY-SA 3.0'],
                ['institut-pertanian-bogor-2.jpg', 'Masjid Al Hurriyah IPB', 'Hysocc, CC BY-SA 3.0'],
                ['institut-pertanian-bogor-3.jpg', 'Gedung A1 TPB IPB', 'Hysocc, CC BY-SA 3.0'],
            ],
        ];
    }
}
