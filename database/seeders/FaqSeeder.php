<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        Faq::where('scope_type', Faq::SCOPE_GLOBAL)->delete();

        foreach ($this->globalFaqs() as $sort => [$question, $answer]) {
            Faq::create([
                'scope_type' => Faq::SCOPE_GLOBAL,
                'scope_key' => null,
                'question' => $question,
                'answer' => $answer,
                'sort' => $sort,
            ]);
        }
    }

    /**
     * @return array<int, array{0: string, 1: string}>
     */
    private function globalFaqs(): array
    {
        return [
            ['Apakah mendaftar lewat platform ini gratis?', 'Ya. Mendaftar akun dan berkonsultasi tidak dipungut biaya. Biaya kuliah mengikuti kampus yang kamu pilih.'],
            ['Apakah biaya kuliah bisa dicicil?', 'Banyak kampus menyediakan pembayaran per bulan. Nilai angsuran tertera pada setiap program studi.'],
            ['Apa bedanya program karyawan dan reguler?', 'Program karyawan memakai jadwal fleksibel untuk yang sudah bekerja. Program reguler memakai jadwal terstruktur untuk lulusan SMA.'],
            ['Apa itu Rekognisi Pembelajaran Lampau?', 'Program yang mengakui pengalaman kerja dan pendidikan sebelumnya sebagai SKS sehingga beban studi lebih ringan.'],
            ['Apakah saya bisa mendaftar ke lebih dari satu kampus?', 'Bisa. Isi formulir pendaftaran untuk setiap kampus yang kamu minati.'],
            ['Siapa yang menjamin data kampus dan biaya benar?', 'Data dikelola oleh pihak kampus dan diperiksa pengelola platform sebelum tampil.'],
        ];
    }
}
