<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\Major;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->careers() as $data) {
            $majorNames = $data['majors'];
            unset($data['majors']);

            $data['slug'] = Str::slug($data['name']);

            $career = Career::updateOrCreate(['slug' => $data['slug']], $data);

            $career->majors()->sync(
                Major::whereIn('name', $majorNames)->pluck('id'),
            );
        }
    }

    private function careers(): array
    {
        return [
            $this->career('Software Engineer', 'Merancang, membangun, dan memelihara perangkat lunak untuk berbagai kebutuhan bisnis dan pengguna.', 7000000, 20000000, ['Junior Engineer', 'Software Engineer', 'Senior Engineer', 'Engineering Manager'], ['Teknik Informatika', 'Ilmu Komputer', 'Sistem Informasi']),
            $this->career('Data Scientist', 'Menganalisis data berskala besar dan membangun model prediksi untuk mendukung keputusan organisasi.', 9000000, 25000000, ['Data Analyst', 'Data Scientist', 'Senior Data Scientist', 'Head of Data'], ['Ilmu Komputer', 'Statistika', 'Matematika']),
            $this->career('System Analyst', 'Menganalisis kebutuhan bisnis dan merancang sistem informasi yang menjawab kebutuhan tersebut.', 6000000, 14000000, ['Junior Analyst', 'System Analyst', 'Senior Analyst', 'IT Manager'], ['Sistem Informasi', 'Teknik Informatika']),
            $this->career('Konsultan Teknologi Informasi', 'Memberi saran strategi dan implementasi teknologi bagi perusahaan agar proses bisnis lebih efisien.', 7000000, 18000000, ['Associate Consultant', 'Consultant', 'Senior Consultant', 'Partner'], ['Sistem Informasi', 'Teknik Informatika']),
            $this->career('Insinyur Sipil', 'Merencanakan dan mengawasi pembangunan infrastruktur seperti gedung, jalan, dan jembatan.', 5500000, 15000000, ['Site Engineer', 'Civil Engineer', 'Project Manager', 'Construction Director'], ['Teknik Sipil']),
            $this->career('Insinyur Mesin', 'Merancang dan merawat mesin serta sistem mekanik di industri manufaktur dan energi.', 5500000, 14000000, ['Junior Engineer', 'Mechanical Engineer', 'Senior Engineer', 'Plant Manager'], ['Teknik Mesin']),
            $this->career('Insinyur Elektro', 'Merancang sistem kelistrikan, elektronika, dan otomasi untuk industri dan infrastruktur.', 5500000, 15000000, ['Junior Engineer', 'Electrical Engineer', 'Senior Engineer', 'Engineering Manager'], ['Teknik Elektro']),
            $this->career('Arsitek', 'Merancang bangunan dan ruang yang fungsional, aman, dan estetis sesuai kebutuhan klien.', 5000000, 16000000, ['Junior Architect', 'Architect', 'Senior Architect', 'Principal Architect'], ['Arsitektur']),
            $this->career('Manajer Operasional', 'Mengatur proses operasional harian perusahaan agar efisien dan mencapai target.', 7000000, 18000000, ['Supervisor', 'Operations Manager', 'Senior Manager', 'Director of Operations'], ['Manajemen']),
            $this->career('Akuntan', 'Mencatat, menganalisis, dan melaporkan kondisi keuangan perusahaan sesuai standar akuntansi.', 5000000, 13000000, ['Staff Akuntansi', 'Akuntan', 'Senior Akuntan', 'Finance Manager'], ['Akuntansi']),
            $this->career('Auditor', 'Memeriksa laporan keuangan dan pengendalian internal untuk memastikan kepatuhan dan akurasi.', 6000000, 16000000, ['Junior Auditor', 'Auditor', 'Senior Auditor', 'Audit Manager'], ['Akuntansi', 'Ilmu Ekonomi']),
            $this->career('Konsultan Pajak', 'Memberi layanan konsultasi perpajakan agar kewajiban pajak dikelola secara efisien dan legal.', 4500000, 9000000, ['Associate Tax Consultant', 'Tax Consultant', 'Tax Manager', 'Tax Partner'], ['Akuntansi', 'Ilmu Ekonomi']),
            $this->career('Ekonom', 'Meneliti dan menganalisis kondisi ekonomi untuk menyusun kebijakan dan prediksi pasar.', 6000000, 17000000, ['Research Assistant', 'Ekonom', 'Senior Ekonom', 'Chief Economist'], ['Ilmu Ekonomi']),
            $this->career('Digital Marketer', 'Merencanakan dan menjalankan strategi pemasaran digital untuk meningkatkan penjualan dan merek.', 5000000, 12000000, ['Marketing Executive', 'Digital Marketer', 'Marketing Manager', 'Head of Marketing'], ['Bisnis Digital', 'Ilmu Komunikasi', 'Manajemen']),
            $this->career('Dokter Umum', 'Mendiagnosis dan menangani penyakit serta memberi layanan kesehatan dasar kepada pasien.', 8000000, 25000000, ['Dokter Internship', 'Dokter Umum', 'Dokter Spesialis', 'Kepala Instalasi'], ['Kedokteran']),
            $this->career('Apoteker', 'Mengelola obat, memastikan penggunaannya aman, dan memberi edukasi obat kepada masyarakat.', 5000000, 12000000, ['Asisten Apoteker', 'Apoteker', 'Apoteker Penanggung Jawab', 'Manajer Farmasi'], ['Farmasi']),
            $this->career('Perawat', 'Memberi asuhan keperawatan dan mendampingi pasien selama proses perawatan.', 4000000, 10000000, ['Perawat Pelaksana', 'Perawat Senior', 'Kepala Ruangan', 'Manajer Keperawatan'], ['Ilmu Keperawatan']),
            $this->career('Ahli Kesehatan Masyarakat', 'Merancang program pencegahan penyakit dan promosi kesehatan bagi komunitas.', 5000000, 12000000, ['Staf Promkes', 'Epidemiolog', 'Konsultan Kesehatan', 'Kepala Dinas'], ['Kesehatan Masyarakat']),
            $this->career('Pengacara', 'Memberi nasihat hukum dan mewakili klien dalam proses peradilan maupun di luar pengadilan.', 5000000, 20000000, ['Junior Associate', 'Advokat', 'Senior Associate', 'Managing Partner'], ['Hukum']),
            $this->career('Psikolog', 'Menilai dan membantu masalah perilaku, emosi, dan perkembangan individu atau organisasi.', 5000000, 13000000, ['Asisten Psikolog', 'Psikolog', 'Psikolog Senior', 'Direktur Layanan Psikologi'], ['Psikologi']),
            $this->career('Jurnalis', 'Mencari, memverifikasi, dan menyajikan berita kepada publik melalui berbagai media.', 4000000, 10000000, ['Reporter', 'Jurnalis', 'Redaktur', 'Pemimpin Redaksi'], ['Ilmu Komunikasi']),
            $this->career('Diplomat', 'Mewakili kepentingan negara dalam hubungan internasional dan perundingan antarnegara.', 7000000, 20000000, ['Atase', 'Sekretaris', 'Konselor', 'Duta Besar'], ['Hubungan Internasional']),
            $this->career('Guru Sekolah Dasar', 'Mendidik dan membimbing siswa sekolah dasar dalam aspek akademik dan karakter.', 3500000, 8000000, ['Guru Kelas', 'Guru Utama', 'Kepala Sekolah', 'Pengawas Sekolah'], ['Pendidikan Guru Sekolah Dasar']),
            $this->career('Konsultan Statistik', 'Membantu organisasi merancang survei dan menganalisis data statistik untuk pengambilan keputusan.', 6000000, 15000000, ['Analis Statistik', 'Konsultan Statistik', 'Senior Konsultan', 'Principal'], ['Statistika', 'Matematika']),
            $this->career('Ahli Agronomi', 'Meningkatkan produksi dan kualitas tanaman melalui teknik budidaya dan teknologi pertanian.', 4500000, 11000000, ['Asisten Agronomis', 'Agronomis', 'Konsultan Pertanian', 'Manajer Perkebunan'], ['Agroteknologi']),
            $this->career('Desainer Grafis', 'Menerjemahkan pesan dan merek menjadi karya visual untuk media cetak dan digital.', 4000000, 11000000, ['Junior Designer', 'Graphic Designer', 'Art Director', 'Creative Director'], ['Desain Komunikasi Visual']),
            $this->career('UI/UX Designer', 'Merancang pengalaman dan antarmuka produk digital yang mudah dan menyenangkan digunakan.', 6000000, 16000000, ['Junior Designer', 'UI/UX Designer', 'Senior Designer', 'Head of Design'], ['Desain Komunikasi Visual', 'Teknik Informatika']),
        ];
    }

    private function career(
        string $name,
        string $description,
        int $salaryMin,
        int $salaryMax,
        array $positions,
        array $majors,
    ): array {
        return [
            'name' => $name,
            'description' => $description,
            'salary_min' => $salaryMin,
            'salary_max' => $salaryMax,
            'positions' => $positions,
            'majors' => $majors,
        ];
    }
}
