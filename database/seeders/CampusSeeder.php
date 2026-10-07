<?php

namespace Database\Seeders;

use App\Enums\VerificationStatus;
use App\Models\Campus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CampusSeeder extends Seeder
{
    // Nama jalur, hari mulai, hari tutup relatif terhadap hari seeding.
    private const ADMISSION_PERIODS = [
        ['Gelombang 1', -30, 30],
        ['Gelombang 2', 31, 90],
    ];

    public function __construct(private readonly StudyProgramSeeder $programs) {}

    public function run(): void
    {
        foreach ($this->campuses() as $data) {
            $majorNames = $data['majors'];
            unset($data['majors']);

            $data['slug'] = Str::slug($data['name']);
            $data['verification_status'] = VerificationStatus::Verified;
            $data['verified_at'] = now();
            $data['logo'] = $this->logoPath($data['slug']);

            $campus = Campus::updateOrCreate(
                ['slug' => $data['slug']],
                $data,
            );

            $this->programs->seedForCampus($campus, $majorNames);
            $this->seedAdmissionPeriods($campus);
        }
    }

    /**
     * Logo dipasang hanya bila berkasnya ada di public/images/logos, bernama slug kampus.
     */
    private function logoPath(string $slug): ?string
    {
        $files = glob(public_path("images/logos/{$slug}.*")) ?: [];

        return $files === [] ? null : 'images/logos/'.basename($files[0]);
    }

    private function seedAdmissionPeriods(Campus $campus): void
    {
        $campus->admissionPeriods()->delete();

        foreach (self::ADMISSION_PERIODS as [$name, $opensOffset, $closesOffset]) {
            $campus->admissionPeriods()->create([
                'name' => $name,
                'opens_at' => today()->addDays($opensOffset),
                'closes_at' => today()->addDays($closesOffset),
            ]);
        }
    }

    private function campuses(): array
    {
        return [
            [
                'name' => 'Universitas Indonesia',
                'type' => Campus::TYPE_NEGERI,
                'form' => 'universitas',
                'city' => 'Depok',
                'province' => 'Jawa Barat',
                'accreditation' => 'Unggul',
                'description' => 'Universitas Indonesia adalah perguruan tinggi negeri tertua di Indonesia dengan reputasi akademik yang kuat di berbagai bidang ilmu.',
                'established_year' => 1849,
                'website' => 'https://ui.ac.id',
                'majors' => ['Kedokteran', 'Hukum', 'Ilmu Ekonomi', 'Teknik Sipil', 'Teknik Mesin', 'Teknik Elektro', 'Ilmu Komputer', 'Psikologi', 'Ilmu Komunikasi', 'Akuntansi', 'Manajemen', 'Farmasi', 'Kesehatan Masyarakat'],
            ],
            [
                'name' => 'Universitas Gadjah Mada',
                'type' => Campus::TYPE_NEGERI,
                'form' => 'universitas',
                'city' => 'Yogyakarta',
                'province' => 'DI Yogyakarta',
                'accreditation' => 'Unggul',
                'description' => 'Universitas Gadjah Mada adalah universitas negeri terkemuka di Yogyakarta yang dikenal dengan keunggulan riset dan pengabdian masyarakat.',
                'established_year' => 1949,
                'website' => 'https://ugm.ac.id',
                'majors' => ['Kedokteran', 'Hukum', 'Ilmu Ekonomi', 'Teknik Sipil', 'Teknik Mesin', 'Teknik Elektro', 'Ilmu Komputer', 'Psikologi', 'Ilmu Komunikasi', 'Akuntansi', 'Manajemen', 'Farmasi', 'Agroteknologi', 'Matematika'],
            ],
            [
                'name' => 'Institut Teknologi Bandung',
                'type' => Campus::TYPE_NEGERI,
                'form' => 'institut',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'accreditation' => 'Unggul',
                'description' => 'Institut Teknologi Bandung adalah perguruan tinggi negeri terkemuka di bidang sains, teknologi, dan seni.',
                'established_year' => 1959,
                'website' => 'https://itb.ac.id',
                'majors' => ['Teknik Informatika', 'Teknik Sipil', 'Teknik Mesin', 'Teknik Elektro', 'Arsitektur', 'Matematika', 'Statistika', 'Desain Komunikasi Visual', 'Ilmu Komputer'],
            ],
            [
                'name' => 'Universitas Airlangga',
                'type' => Campus::TYPE_NEGERI,
                'form' => 'universitas',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'accreditation' => 'Unggul',
                'description' => 'Universitas Airlangga adalah perguruan tinggi negeri di Surabaya yang unggul di bidang kesehatan, hukum, dan sosial humaniora.',
                'established_year' => 1954,
                'website' => 'https://unair.ac.id',
                'majors' => ['Kedokteran', 'Farmasi', 'Hukum', 'Ilmu Ekonomi', 'Akuntansi', 'Manajemen', 'Psikologi', 'Kesehatan Masyarakat', 'Ilmu Keperawatan'],
            ],
            [
                'name' => 'Institut Pertanian Bogor',
                'type' => Campus::TYPE_NEGERI,
                'form' => 'institut',
                'city' => 'Bogor',
                'province' => 'Jawa Barat',
                'accreditation' => 'Unggul',
                'description' => 'Institut Pertanian Bogor adalah perguruan tinggi negeri yang berfokus pada pertanian, kelautan, dan biosains tropika.',
                'established_year' => 1963,
                'website' => 'https://ipb.ac.id',
                'majors' => ['Agroteknologi', 'Statistika', 'Matematika', 'Ilmu Ekonomi', 'Bisnis Digital', 'Kesehatan Masyarakat', 'Ilmu Komputer'],
            ],
            [
                'name' => 'Universitas Brawijaya',
                'type' => Campus::TYPE_NEGERI,
                'form' => 'universitas',
                'city' => 'Malang',
                'province' => 'Jawa Timur',
                'accreditation' => 'Unggul',
                'description' => 'Universitas Brawijaya adalah perguruan tinggi negeri di Malang dengan program studi yang luas dan suasana kampus yang hidup.',
                'established_year' => 1963,
                'website' => 'https://ub.ac.id',
                'majors' => ['Hukum', 'Ilmu Ekonomi', 'Manajemen', 'Akuntansi', 'Teknik Informatika', 'Ilmu Komunikasi', 'Psikologi', 'Agroteknologi', 'Statistika'],
            ],
            [
                'name' => 'Universitas Diponegoro',
                'type' => Campus::TYPE_NEGERI,
                'form' => 'universitas',
                'city' => 'Semarang',
                'province' => 'Jawa Tengah',
                'accreditation' => 'Unggul',
                'description' => 'Universitas Diponegoro adalah perguruan tinggi negeri di Semarang yang dikenal kuat di bidang teknik dan kesehatan.',
                'established_year' => 1957,
                'website' => 'https://undip.ac.id',
                'majors' => ['Teknik Sipil', 'Teknik Mesin', 'Teknik Elektro', 'Arsitektur', 'Hukum', 'Ilmu Ekonomi', 'Manajemen', 'Akuntansi', 'Kesehatan Masyarakat'],
            ],
            [
                'name' => 'Universitas Padjadjaran',
                'type' => Campus::TYPE_NEGERI,
                'form' => 'universitas',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'accreditation' => 'Unggul',
                'description' => 'Universitas Padjadjaran adalah perguruan tinggi negeri di Bandung dengan reputasi di bidang hukum, kedokteran, dan komunikasi.',
                'established_year' => 1957,
                'website' => 'https://unpad.ac.id',
                'majors' => ['Kedokteran', 'Hukum', 'Ilmu Komunikasi', 'Hubungan Internasional', 'Psikologi', 'Farmasi', 'Ilmu Ekonomi', 'Manajemen', 'Akuntansi'],
            ],
            [
                'name' => 'Universitas Pasundan',
                'type' => Campus::TYPE_SWASTA,
                'form' => 'universitas',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'accreditation' => 'Baik Sekali',
                'description' => 'Universitas Pasundan adalah perguruan tinggi swasta tertua di Bandung yang berada di bawah naungan Paguyuban Pasundan.',
                'established_year' => 1960,
                'website' => 'https://unpas.ac.id',
                'majors' => ['Hukum', 'Manajemen', 'Akuntansi', 'Teknik Informatika', 'Ilmu Komunikasi', 'Desain Komunikasi Visual'],
            ],
            [
                'name' => 'Universitas Matana',
                'type' => Campus::TYPE_SWASTA,
                'form' => 'universitas',
                'city' => 'Tangerang',
                'province' => 'Banten',
                'accreditation' => 'Baik',
                'description' => 'Universitas Matana adalah perguruan tinggi swasta di Tangerang yang berfokus pada bisnis, komunikasi, dan teknologi.',
                'established_year' => 2014,
                'website' => 'https://matanauniversity.ac.id',
                'majors' => ['Manajemen', 'Akuntansi', 'Bisnis Digital', 'Desain Komunikasi Visual', 'Ilmu Komunikasi', 'Teknik Informatika'],
            ],
            [
                'name' => 'Universitas Garut',
                'type' => Campus::TYPE_SWASTA,
                'form' => 'universitas',
                'city' => 'Garut',
                'province' => 'Jawa Barat',
                'accreditation' => 'Baik',
                'description' => 'Universitas Garut adalah perguruan tinggi swasta di Garut yang menyediakan pendidikan terjangkau untuk masyarakat setempat.',
                'established_year' => 1998,
                'website' => 'https://uniga.ac.id',
                'majors' => ['Manajemen', 'Akuntansi', 'Hukum', 'Agroteknologi', 'Pendidikan Guru Sekolah Dasar', 'Ilmu Komunikasi'],
            ],
            [
                'name' => 'Universitas Perintis Indonesia',
                'type' => Campus::TYPE_SWASTA,
                'form' => 'universitas',
                'city' => 'Padang',
                'province' => 'Sumatera Barat',
                'accreditation' => 'Baik',
                'description' => 'Universitas Perintis Indonesia adalah perguruan tinggi swasta di Sumatera Barat yang berkembang dari sekolah tinggi ilmu kesehatan.',
                'established_year' => 2019,
                'website' => null,
                'majors' => ['Ilmu Keperawatan', 'Farmasi', 'Kesehatan Masyarakat', 'Ilmu Komunikasi', 'Bisnis Digital'],
            ],
            [
                'name' => 'Sekolah Tinggi Ilmu Ekonomi Bisnis Indonesia',
                'type' => Campus::TYPE_SWASTA,
                'form' => 'sekolah-tinggi',
                'city' => 'Jakarta Barat',
                'province' => 'DKI Jakarta',
                'accreditation' => 'Baik',
                'description' => 'Sekolah Tinggi Ilmu Ekonomi Bisnis Indonesia adalah perguruan tinggi swasta di Jakarta yang berfokus pada ekonomi dan bisnis.',
                'established_year' => 2007,
                'website' => null,
                'majors' => ['Manajemen', 'Akuntansi', 'Bisnis Digital'],
            ],
            [
                'name' => 'Politeknik Jambi',
                'type' => Campus::TYPE_SWASTA,
                'form' => 'politeknik',
                'city' => 'Jambi',
                'province' => 'Jambi',
                'accreditation' => 'Baik',
                'description' => 'Politeknik Jambi adalah perguruan tinggi vokasi swasta pertama di Provinsi Jambi.',
                'established_year' => 2003,
                'website' => null,
                'majors' => ['Bisnis Digital', 'Manajemen', 'Akuntansi', 'Teknik Elektro'],
            ],
            [
                'name' => 'Universitas Haji Sumatera Utara',
                'type' => Campus::TYPE_SWASTA,
                'form' => 'universitas',
                'city' => 'Medan',
                'province' => 'Sumatera Utara',
                'accreditation' => 'Baik',
                'description' => 'Universitas Haji Sumatera Utara adalah perguruan tinggi swasta di Sumatera Utara yang berfokus pada bidang kesehatan dan pendidikan.',
                'established_year' => 2020,
                'website' => null,
                'majors' => ['Ilmu Keperawatan', 'Farmasi', 'Kesehatan Masyarakat', 'Hukum', 'Manajemen', 'Pendidikan Guru Sekolah Dasar'],
            ],
            [
                'name' => 'Akademi Maritim Nasional Jaya',
                'type' => Campus::TYPE_SWASTA,
                'form' => 'akademi',
                'city' => 'Jakarta Utara',
                'province' => 'DKI Jakarta',
                'accreditation' => 'Baik',
                'description' => 'Akademi Maritim Nasional Jaya adalah perguruan tinggi vokasi di bawah naungan TNI Angkatan Laut yang berfokus pada bidang kemaritiman.',
                'established_year' => 1986,
                'website' => null,
                'majors' => ['Teknik Elektro', 'Teknik Mesin'],
            ],
            [
                'name' => 'Politeknik Keuangan Negara STAN',
                'type' => Campus::TYPE_KEDINASAN,
                'form' => 'politeknik',
                'city' => 'Tangerang Selatan',
                'province' => 'Banten',
                'accreditation' => 'Unggul',
                'description' => 'Politeknik Keuangan Negara STAN adalah perguruan tinggi kedinasan yang mencetak sumber daya manusia di bidang keuangan negara.',
                'established_year' => 2015,
                'website' => 'https://pknstan.ac.id',
                'majors' => ['Akuntansi', 'Manajemen'],
            ],
            [
                'name' => 'Institut Pemerintahan Dalam Negeri',
                'type' => Campus::TYPE_KEDINASAN,
                'form' => 'institut',
                'city' => 'Sumedang',
                'province' => 'Jawa Barat',
                'accreditation' => 'Unggul',
                'description' => 'Institut Pemerintahan Dalam Negeri adalah perguruan tinggi kedinasan yang menyiapkan kader pemerintahan dalam negeri.',
                'established_year' => 2004,
                'website' => 'https://ipdn.ac.id',
                'majors' => ['Hukum', 'Manajemen', 'Ilmu Komunikasi'],
            ],
        ];
    }
}
