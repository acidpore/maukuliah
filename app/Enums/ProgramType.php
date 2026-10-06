<?php

namespace App\Enums;

enum ProgramType: string
{
    case Karyawan = 'karyawan';
    case Reguler = 'reguler';
    case Rpl = 'rpl';
    case Akselerasi = 'akselerasi';
    case Shift = 'shift';

    public function label(): string
    {
        return match ($this) {
            self::Karyawan => 'Program Perkuliahan Karyawan',
            self::Reguler => 'Program Perkuliahan Reguler',
            self::Rpl => 'Rekognisi Pembelajaran Lampau',
            self::Akselerasi => 'Program Perkuliahan Akselerasi',
            self::Shift => 'Program Kuliah Shift',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Karyawan => 'Dirancang untuk yang sudah bekerja, dengan jadwal fleksibel dan biaya yang dapat dicicil.',
            self::Reguler => 'Untuk lulusan SMA atau setara yang ingin langsung melanjutkan kuliah dengan jadwal terstruktur.',
            self::Rpl => 'Mengonversi pengalaman kerja dan pendidikan sebelumnya menjadi SKS yang diakui perguruan tinggi.',
            self::Akselerasi => 'Masa studi lebih singkat bagi yang ingin menyelesaikan S1 dan S2 lebih cepat.',
            self::Shift => 'Jadwal di luar jam kerja biasa, seperti malam hari atau akhir pekan.',
        };
    }
}
