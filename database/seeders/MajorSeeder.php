<?php

namespace Database\Seeders;

use App\Models\Major;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MajorSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->majors() as $major) {
            $major['slug'] = Str::slug($major['name']);

            Major::updateOrCreate(
                ['slug' => $major['slug']],
                $major,
            );
        }
    }

    private function majors(): array
    {
        return [
            [
                'name' => 'Teknik Informatika',
                'category' => 'Komputer & Informatika',
                'description' => 'Mempelajari pengembangan perangkat lunak, algoritma, struktur data, dan teknologi informasi untuk membangun solusi digital.',
                'courses' => ['Algoritma dan Pemrograman', 'Struktur Data', 'Basis Data', 'Pemrograman Web', 'Jaringan Komputer', 'Kecerdasan Buatan'],
                'career_prospects' => ['Software Engineer', 'Web Developer', 'Data Engineer', 'System Analyst', 'DevOps Engineer'],
            ],
            [
                'name' => 'Sistem Informasi',
                'category' => 'Komputer & Informatika',
                'description' => 'Menggabungkan teknologi informasi dan manajemen bisnis untuk merancang sistem yang efektif bagi organisasi.',
                'courses' => ['Analisis dan Perancangan Sistem', 'Manajemen Basis Data', 'Sistem Enterprise', 'E-Commerce', 'Tata Kelola TI'],
                'career_prospects' => ['System Analyst', 'Business Analyst', 'IT Consultant', 'Product Manager', 'Database Administrator'],
            ],
            [
                'name' => 'Ilmu Komputer',
                'category' => 'Komputer & Informatika',
                'description' => 'Berfokus pada teori komputasi, algoritma, dan pengembangan teknologi komputasi modern.',
                'courses' => ['Matematika Diskrit', 'Teori Komputasi', 'Algoritma Lanjut', 'Sistem Operasi', 'Machine Learning'],
                'career_prospects' => ['Data Scientist', 'Machine Learning Engineer', 'Researcher', 'Software Architect', 'Backend Engineer'],
            ],
            [
                'name' => 'Teknik Sipil',
                'category' => 'Teknik & Teknologi',
                'description' => 'Merancang dan membangun infrastruktur seperti gedung, jalan, jembatan, dan bendungan.',
                'courses' => ['Mekanika Tanah', 'Struktur Beton', 'Hidrologi', 'Manajemen Konstruksi', 'Rekayasa Transportasi'],
                'career_prospects' => ['Civil Engineer', 'Structural Engineer', 'Project Manager', 'Quantity Surveyor', 'Geotechnical Engineer'],
            ],
            [
                'name' => 'Teknik Mesin',
                'category' => 'Teknik & Teknologi',
                'description' => 'Mempelajari perancangan, pembuatan, dan pemeliharaan mesin serta sistem mekanik.',
                'courses' => ['Termodinamika', 'Mekanika Fluida', 'Elemen Mesin', 'Teknik Pembentukan', 'Konversi Energi'],
                'career_prospects' => ['Mechanical Engineer', 'Design Engineer', 'Maintenance Engineer', 'Energy Analyst', 'Production Engineer'],
            ],
            [
                'name' => 'Teknik Elektro',
                'category' => 'Teknik & Teknologi',
                'description' => 'Mempelajari kelistrikan, elektronika, sistem tenaga, dan telekomunikasi.',
                'courses' => ['Rangkaian Listrik', 'Elektronika', 'Sistem Tenaga', 'Sistem Kendali', 'Telekomunikasi'],
                'career_prospects' => ['Electrical Engineer', 'Power Engineer', 'Automation Engineer', 'Embedded Engineer', 'Telecom Engineer'],
            ],
            [
                'name' => 'Arsitektur',
                'category' => 'Teknik & Teknologi',
                'description' => 'Merancang bangunan yang fungsional, estetis, dan ramah lingkungan.',
                'courses' => ['Studio Perancangan', 'Struktur Bangunan', 'Utilitas', 'Arsitektur Lansekap', 'Sejarah Arsitektur'],
                'career_prospects' => ['Architect', 'Urban Designer', 'Interior Designer', 'BIM Specialist', 'Design Consultant'],
            ],
            [
                'name' => 'Manajemen',
                'category' => 'Ekonomi & Bisnis',
                'description' => 'Mempelajari pengelolaan organisasi, sumber daya, dan strategi bisnis secara efektif.',
                'courses' => ['Manajemen Pemasaran', 'Manajemen Keuangan', 'Manajemen SDM', 'Perilaku Organisasi', 'Manajemen Strategi'],
                'career_prospects' => ['Marketing Manager', 'HR Manager', 'Business Development', 'Operations Manager', 'Entrepreneur'],
            ],
            [
                'name' => 'Akuntansi',
                'category' => 'Ekonomi & Bisnis',
                'description' => 'Mempelajari pencatatan, pelaporan, dan analisis keuangan organisasi.',
                'courses' => ['Akuntansi Keuangan', 'Akuntansi Biaya', 'Auditing', 'Perpajakan', 'Sistem Informasi Akuntansi'],
                'career_prospects' => ['Auditor', 'Accountant', 'Tax Consultant', 'Financial Analyst', 'Controller'],
            ],
            [
                'name' => 'Ilmu Ekonomi',
                'category' => 'Ekonomi & Bisnis',
                'description' => 'Mempelajari perilaku ekonomi mikro dan makro serta kebijakan publik.',
                'courses' => ['Mikroekonomi', 'Makroekonomi', 'Ekonometrika', 'Ekonomi Pembangunan', 'Ekonomi Internasional'],
                'career_prospects' => ['Economist', 'Policy Analyst', 'Research Analyst', 'Bank Officer', 'Data Analyst'],
            ],
            [
                'name' => 'Bisnis Digital',
                'category' => 'Ekonomi & Bisnis',
                'description' => 'Menggabungkan bisnis dan teknologi digital untuk membangun model bisnis modern.',
                'courses' => ['Digital Marketing', 'E-Commerce', 'Analitik Bisnis', 'Desain Produk Digital', 'Kewirausahaan Digital'],
                'career_prospects' => ['Digital Marketer', 'E-Commerce Manager', 'Growth Specialist', 'Product Manager', 'Startup Founder'],
            ],
            [
                'name' => 'Kedokteran',
                'category' => 'Kesehatan',
                'description' => 'Mempelajari ilmu kedokteran untuk diagnosis, pengobatan, dan pencegahan penyakit.',
                'courses' => ['Anatomi', 'Fisiologi', 'Farmakologi', 'Patologi', 'Ilmu Penyakit Dalam'],
                'career_prospects' => ['Dokter', 'Dokter Spesialis', 'Peneliti Medis', 'Dosen Kedokteran', 'Konsultan Kesehatan'],
            ],
            [
                'name' => 'Farmasi',
                'category' => 'Kesehatan',
                'description' => 'Mempelajari ilmu obat-obatan, formulasi, dan pelayanan kefarmasian.',
                'courses' => ['Kimia Farmasi', 'Farmakologi', 'Farmasetika', 'Farmakognosi', 'Farmasi Klinis'],
                'career_prospects' => ['Apoteker', 'Formulation Scientist', 'Regulatory Affairs', 'Quality Control', 'Medical Representative'],
            ],
            [
                'name' => 'Ilmu Keperawatan',
                'category' => 'Kesehatan',
                'description' => 'Mempelajari asuhan keperawatan dan pelayanan kesehatan kepada pasien.',
                'courses' => ['Keperawatan Dasar', 'Keperawatan Medikal Bedah', 'Keperawatan Anak', 'Keperawatan Jiwa', 'Manajemen Keperawatan'],
                'career_prospects' => ['Perawat', 'Nurse Educator', 'Perawat Spesialis', 'Manajer Keperawatan', 'Home Care Nurse'],
            ],
            [
                'name' => 'Kesehatan Masyarakat',
                'category' => 'Kesehatan',
                'description' => 'Mempelajari pencegahan penyakit dan peningkatan kesehatan masyarakat.',
                'courses' => ['Epidemiologi', 'Biostatistika', 'Kesehatan Lingkungan', 'Kebijakan Kesehatan', 'Gizi Masyarakat'],
                'career_prospects' => ['Public Health Officer', 'Epidemiologist', 'Health Policy Analyst', 'Program Manager', 'Health Promoter'],
            ],
            [
                'name' => 'Hukum',
                'category' => 'Hukum',
                'description' => 'Mempelajari norma, peraturan, dan sistem hukum dalam masyarakat.',
                'courses' => ['Hukum Perdata', 'Hukum Pidana', 'Hukum Tata Negara', 'Hukum Internasional', 'Hukum Bisnis'],
                'career_prospects' => ['Advokat', 'Hakim', 'Jaksa', 'Legal Counsel', 'Notaris'],
            ],
            [
                'name' => 'Psikologi',
                'category' => 'Sosial & Humaniora',
                'description' => 'Mempelajari perilaku dan proses mental manusia secara ilmiah.',
                'courses' => ['Psikologi Umum', 'Psikologi Perkembangan', 'Psikologi Sosial', 'Psikologi Klinis', 'Psikometri'],
                'career_prospects' => ['Psikolog', 'HR Specialist', 'Konselor', 'UX Researcher', 'Trainer'],
            ],
            [
                'name' => 'Ilmu Komunikasi',
                'category' => 'Sosial & Humaniora',
                'description' => 'Mempelajari proses komunikasi massa, media, dan hubungan masyarakat.',
                'courses' => ['Teori Komunikasi', 'Jurnalistik', 'Public Relations', 'Komunikasi Pemasaran', 'Produksi Media'],
                'career_prospects' => ['Public Relations', 'Jurnalis', 'Content Strategist', 'Media Planner', 'Corporate Communication'],
            ],
            [
                'name' => 'Hubungan Internasional',
                'category' => 'Sosial & Humaniora',
                'description' => 'Mempelajari hubungan antarnegara, diplomasi, dan politik global.',
                'courses' => ['Teori Hubungan Internasional', 'Diplomasi', 'Hukum Internasional', 'Ekonomi Politik Global', 'Studi Kawasan'],
                'career_prospects' => ['Diplomat', 'Policy Analyst', 'International Affairs Officer', 'Researcher', 'NGO Officer'],
            ],
            [
                'name' => 'Pendidikan Guru Sekolah Dasar',
                'category' => 'Pendidikan',
                'description' => 'Menyiapkan calon guru sekolah dasar yang profesional dan berkarakter.',
                'courses' => ['Pedagogik', 'Psikologi Pendidikan', 'Kurikulum SD', 'Media Pembelajaran', 'Evaluasi Pendidikan'],
                'career_prospects' => ['Guru SD', 'Pengembang Kurikulum', 'Konsultan Pendidikan', 'Kepala Sekolah', 'Edupreneur'],
            ],
            [
                'name' => 'Matematika',
                'category' => 'Sains & Matematika',
                'description' => 'Mempelajari struktur, pola, dan logika matematis secara mendalam.',
                'courses' => ['Kalkulus', 'Aljabar Linear', 'Analisis Real', 'Persamaan Diferensial', 'Statistika Matematika'],
                'career_prospects' => ['Data Scientist', 'Aktuaris', 'Analyst', 'Dosen Matematika', 'Risk Analyst'],
            ],
            [
                'name' => 'Statistika',
                'category' => 'Sains & Matematika',
                'description' => 'Mempelajari pengumpulan, pengolahan, dan analisis data untuk pengambilan keputusan.',
                'courses' => ['Teori Peluang', 'Statistika Inferensial', 'Analisis Regresi', 'Analisis Multivariat', 'Data Mining'],
                'career_prospects' => ['Statistician', 'Data Analyst', 'Data Scientist', 'Business Intelligence', 'Risk Analyst'],
            ],
            [
                'name' => 'Agroteknologi',
                'category' => 'Pertanian',
                'description' => 'Mempelajari teknologi produksi tanaman dan pengelolaan lahan secara berkelanjutan.',
                'courses' => ['Agronomi', 'Ilmu Tanah', 'Hama dan Penyakit Tanaman', 'Pemuliaan Tanaman', 'Teknologi Benih'],
                'career_prospects' => ['Agronomist', 'Plant Breeder', 'Field Agronomist', 'Agricultural Researcher', 'Product Development'],
            ],
            [
                'name' => 'Desain Komunikasi Visual',
                'category' => 'Seni & Desain',
                'description' => 'Mempelajari komunikasi visual melalui desain grafis dan media digital.',
                'courses' => ['Tipografi', 'Ilustrasi', 'Desain Grafis', 'Fotografi', 'Animasi', 'Branding'],
                'career_prospects' => ['Graphic Designer', 'UI/UX Designer', 'Art Director', 'Illustrator', 'Motion Designer'],
            ],
        ];
    }
}
