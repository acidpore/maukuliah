<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Scholarship;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ScholarshipSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->scholarships() as $data) {
            $campusName = $data['campus'];
            unset($data['campus']);

            $data['slug'] = Str::slug($data['name']);
            $data['campus_id'] = $campusName
                ? Campus::where('name', $campusName)->value('id')
                : null;

            Scholarship::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }

    private function scholarships(): array
    {
        return [
            $this->scholarship('KIP Kuliah Merdeka', 'Bantuan biaya pendidikan dari pemerintah bagi lulusan SMA/SMK sederajat yang berprestasi namun terkendala ekonomi.', 'Kemdiktisaintek', -30, 60, null),
            $this->scholarship('Beasiswa Prestasi Akademik Nasional', 'Potongan biaya kuliah bagi peraih peringkat teratas di sekolah dengan nilai rapor terbaik.', 'Yayasan Pendidikan Nusantara', -10, 80, null),
            $this->scholarship('Beasiswa Kedinasan Keuangan Negara', 'Pembebasan biaya pendidikan dan ikatan dinas bagi lulusan terpilih Politeknik Keuangan Negara STAN.', 'Kementerian Keuangan', -5, 45, 'Politeknik Keuangan Negara STAN'),
            $this->scholarship('Beasiswa Kesehatan Sumatera Utara', 'Beasiswa pendidikan program kesehatan bagi calon mahasiswa dari wilayah Sumatera Utara.', 'Universitas Haji Sumatera Utara', 5, 120, 'Universitas Haji Sumatera Utara'),
            $this->scholarship('Beasiswa Vokasi Jambi', 'Beasiswa bagi lulusan SMK yang melanjutkan ke program vokasi unggulan.', 'Politeknik Jambi', 15, 90, 'Politeknik Jambi'),
            $this->scholarship('Beasiswa Prestasi Non-Akademik', 'Beasiswa bagi calon mahasiswa berprestasi di bidang olahraga, seni, dan organisasi.', 'Yayasan Pendidikan Nusantara', -90, -20, null),
            $this->scholarship('Beasiswa Putri Berprestasi', 'Beasiswa untuk calon mahasiswa perempuan berprestasi di bidang sains dan teknologi.', 'Lembaga Beasiswa Mandiri', 30, 150, null),
        ];
    }

    private function scholarship(
        string $name,
        string $description,
        string $provider,
        int $startOffsetDays,
        int $endOffsetDays,
        ?string $campus,
    ): array {
        return [
            'name' => $name,
            'description' => $description,
            'provider' => $provider,
            'start_date' => today()->addDays($startOffsetDays),
            'end_date' => today()->addDays($endOffsetDays),
            'registration_url' => null,
            'campus' => $campus,
        ];
    }
}
