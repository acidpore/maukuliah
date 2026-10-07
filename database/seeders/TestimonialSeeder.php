<?php

namespace Database\Seeders;

use App\Models\Campus;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

/**
 * Testimoni contoh untuk demo. Seluruh nama alumni fiktif dan tidak merujuk tokoh nyata.
 */
class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->testimonials() as $slug => $items) {
            $campus = Campus::where('slug', $slug)->first();

            if ($campus === null) {
                continue;
            }

            foreach ($items as [$name, $major, $job, $year, $quote]) {
                Testimonial::updateOrCreate(
                    ['campus_id' => $campus->id, 'name' => $name],
                    ['major_name' => $major, 'current_job' => $job, 'graduation_year' => $year, 'quote' => $quote],
                );
            }
        }
    }

    /**
     * @return array<string, array<int, array{0: string, 1: string, 2: string, 3: int, 4: string}>>
     */
    private function testimonials(): array
    {
        return [
            'universitas-indonesia' => [
                ['Aditya Nugraha', 'Ilmu Komputer', 'Software Engineer di perusahaan teknologi finansial', 2021, 'Kurikulumnya menantang dan banyak proyek kelompok. Dari situ saya terbiasa bekerja dengan tim dan tenggat ketat.'],
                ['Kirana Maharani', 'Teknik Sipil', 'Insinyur proyek di kontraktor infrastruktur', 2020, 'Praktikum lapangan membuat saya paham pekerjaan sebelum lulus. Jaringan alumninya membantu saya mendapat pekerjaan pertama.'],
            ],
            'universitas-gadjah-mada' => [
                ['Bayu Prakoso', 'Teknik Mesin', 'Engineer manufaktur di pabrik otomotif', 2019, 'Dosen mendorong kami terjun ke riset dan lomba. Pengalaman itu yang paling saya ceritakan saat wawancara kerja.'],
                ['Larasati Putri', 'Ilmu Komputer', 'Data analyst di perusahaan e-commerce', 2022, 'Suasana kampusnya hangat dan komunitas mahasiswanya aktif, jadi mudah menemukan teman belajar yang sefrekuensi.'],
            ],
            'institut-teknologi-bandung' => [
                ['Fajar Hidayat', 'Teknik Informatika', 'Backend developer di startup logistik', 2021, 'Tugas besar tiap semester memaksa saya membangun sistem utuh dari nol. Itu bekal terbaik untuk dunia kerja.'],
                ['Salsabila Azzahra', 'Teknik Sipil', 'Konsultan perencana struktur', 2020, 'Budaya belajar di sini keras tapi saling membantu. Saya tidak akan sampai sini tanpa kelompok belajar angkatan.'],
            ],
            'universitas-airlangga' => [
                ['Rendra Wicaksono', 'Manajemen', 'Brand manager di perusahaan barang konsumsi', 2020, 'Studi kasus nyata di kelas membuat saya terbiasa mengambil keputusan dengan data yang belum lengkap.'],
                ['Intan Permatasari', 'Akuntansi', 'Auditor di kantor akuntan publik', 2021, 'Dosen praktisi sering diundang mengajar, jadi materi audit terasa dekat dengan pekerjaan sehari-hari.'],
            ],
            'institut-pertanian-bogor' => [
                ['Galih Pramudito', 'Bisnis Digital', 'Product manager di platform agritech', 2022, 'Di sini saya belajar bisnis lewat masalah petani yang nyata. Itu yang membuat produk saya punya dampak.'],
                ['Nabila Ardiani', 'Ilmu Ekonomi', 'Analis kebijakan pangan', 2019, 'Diskusi lintas jurusan di asrama memperluas cara pandang saya soal ekonomi desa dan ketahanan pangan.'],
            ],
            'universitas-brawijaya' => [
                ['Yoga Mahendra', 'Teknik Informatika', 'Mobile developer di perusahaan konsultan TI', 2021, 'Laboratorium komputernya lengkap dan asisten dosennya sabar. Saya belajar membangun aplikasi sampai tahap rilis.'],
                ['Dina Safitri', 'Manajemen', 'Staf pengembangan bisnis di perusahaan ritel', 2020, 'Organisasi mahasiswa melatih saya negosiasi dan memimpin tim, hal yang tidak diajarkan di kelas.'],
            ],
            'universitas-diponegoro' => [
                ['Reza Firmansyah', 'Teknik Elektro', 'Teknisi senior di perusahaan kelistrikan', 2018, 'Praktikum rangkaian dan kontrol dikerjakan sendiri, jadi saat bekerja saya tidak canggung memegang peralatan.'],
                ['Citra Anjani', 'Teknik Sipil', 'Pengawas mutu di proyek jalan tol', 2021, 'Kerja praktik di proyek nyata membuka mata saya soal standar mutu dan keselamatan kerja.'],
            ],
            'universitas-padjadjaran' => [
                ['Muhammad Iqbal', 'Ilmu Ekonomi', 'Peneliti di lembaga kajian ekonomi', 2020, 'Dosen membimbing riset sejak semester awal, sehingga skripsi saya terasa seperti kelanjutan, bukan beban akhir.'],
                ['Annisa Rahayu', 'Akuntansi', 'Akuntan keuangan di perusahaan manufaktur', 2022, 'Lingkungan kampus nyaman dan teman-temannya suportif. Saya jadi berani ikut lomba akuntansi tingkat nasional.'],
            ],
            'universitas-pasundan' => [
                ['Deni Kurniawan', 'Teknik Informatika', 'Web developer freelance dan pemilik studio kecil', 2019, 'Kelas malam memungkinkan saya bekerja sambil kuliah. Ilmunya langsung saya pakai di kantor besok harinya.'],
                ['Wulan Sari', 'Manajemen', 'Supervisor operasional di perusahaan distribusi', 2021, 'Biaya terjangkau dan dosennya mudah dihubungi. Saya merasa diperhatikan sebagai mahasiswa, bukan sekadar nomor induk.'],
            ],
            'universitas-matana' => [
                ['Kevin Hartanto', 'Teknik Informatika', 'Quality assurance engineer di perusahaan perangkat lunak', 2022, 'Kelasnya kecil jadi dosen mengenal kami satu per satu. Pertanyaan apa pun biasanya langsung terjawab.'],
                ['Stella Wijaya', 'Akuntansi', 'Staf keuangan di perusahaan multinasional', 2021, 'Program magangnya terarah dan kemampuan bahasa Inggris saya berkembang pesat selama kuliah.'],
            ],
            'universitas-garut' => [
                ['Rizal Fauzi', 'Hukum', 'Asisten advokat di kantor hukum daerah', 2020, 'Kegiatan peradilan semu mengasah cara saya berargumen. Itu sangat terpakai saat mendampingi klien pertama.'],
                ['Neng Siti Aminah', 'Manajemen', 'Pemilik usaha kuliner dan pengelola koperasi', 2019, 'Ilmu manajemen yang saya dapat langsung dipraktikkan untuk usaha keluarga, dari pembukuan sampai pemasaran.'],
            ],
            'universitas-perintis-indonesia' => [
                ['Fitri Handayani', 'Farmasi', 'Apoteker pendamping di apotek rumah sakit', 2021, 'Praktik di apotek dan rumah sakit dimulai sejak awal, jadi saya percaya diri saat mulai bertugas.'],
                ['Hendra Saputra', 'Ilmu Keperawatan', 'Perawat pelaksana di rumah sakit swasta', 2020, 'Dosen yang juga praktisi mengajarkan sikap empati kepada pasien, bukan hanya prosedur tindakan.'],
            ],
            'sekolah-tinggi-ilmu-ekonomi-bisnis-indonesia' => [
                ['Ayu Lestari', 'Bisnis Digital', 'Spesialis pemasaran digital di agensi kreatif', 2022, 'Tugas kuliahnya berupa kampanye nyata untuk UMKM. Portofolio itu yang membuat saya diterima bekerja.'],
                ['Bima Ardiansyah', 'Akuntansi', 'Staf pajak di perusahaan jasa', 2021, 'Jadwal kuliah fleksibel cocok untuk saya yang bekerja. Materi perpajakannya relevan dengan pekerjaan.'],
            ],
            'politeknik-jambi' => [
                ['Syahrul Ramadhan', 'Teknik Elektro', 'Teknisi instalasi listrik di perusahaan energi', 2020, 'Porsi praktiknya besar. Begitu lulus, saya sudah terbiasa membaca gambar instalasi dan memakai alat ukur.'],
                ['Mega Pratiwi', 'Akuntansi', 'Staf administrasi keuangan di instansi daerah', 2021, 'Magang di instansi mengajarkan alur pencatatan yang benar. Itu sangat membantu di pekerjaan pertama.'],
            ],
            'universitas-haji-sumatera-utara' => [
                ['Ahmad Zulkifli', 'Farmasi', 'Tenaga teknis kefarmasian di puskesmas', 2021, 'Suasana belajar religius dan tenang. Dosen sabar membimbing praktikum peracikan obat sampai kami mahir.'],
                ['Rahmi Nasution', 'Ilmu Keperawatan', 'Perawat di klinik pratama', 2020, 'Praktik klinik sejak semester menengah membuat saya terbiasa menghadapi pasien dengan beragam kondisi.'],
            ],
            'akademi-maritim-nasional-jaya' => [
                ['Taufik Hidayatullah', 'Teknik Mesin', 'Masinis pada kapal niaga domestik', 2019, 'Latihan di simulator dan kapal latih membuat saya siap saat pertama kali naik kapal sungguhan.'],
                ['Putra Wirawan', 'Teknik Elektro', 'Teknisi kelistrikan kapal di galangan', 2021, 'Disiplin di asrama berat di awal, tapi sangat berguna untuk kerja shift di atas kapal.'],
            ],
            'politeknik-keuangan-negara-stan' => [
                ['Dimas Anggoro', 'Akuntansi', 'Pelaksana di unit perbendaharaan negara', 2020, 'Pendidikannya ketat dan terstruktur. Kami dibiasakan teliti dengan angka dan aturan sejak hari pertama.'],
                ['Eka Widyastuti', 'Manajemen', 'Analis anggaran di instansi pemerintah', 2022, 'Studi kasus pengelolaan anggaran membuat saya paham alur uang negara dari perencanaan sampai laporan.'],
            ],
            'institut-pemerintahan-dalam-negeri' => [
                ['Satria Nugroho', 'Manajemen', 'Staf pemerintahan di kantor kecamatan', 2020, 'Kehidupan di kampus melatih kepemimpinan dan disiplin. Praktik lapangan di daerah membuka mata soal pelayanan publik.'],
                ['Ratih Kusumawardani', 'Hukum', 'Analis peraturan daerah di sekretariat daerah', 2021, 'Materi hukum tata pemerintahan langsung terpakai saat saya menyusun telaah rancangan peraturan.'],
            ],
        ];
    }
}
