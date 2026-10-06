<?php

namespace Database\Seeders;

use App\Enums\ClassSchedule;
use App\Enums\DegreeLevel;
use App\Enums\LearningMethod;
use App\Enums\ProgramType;
use App\Models\Campus;
use App\Models\Major;
use Illuminate\Database\Seeder;

/**
 * Data contoh program studi. Nilai biaya dan jadwal dibuat deterministik
 * dari nama kampus dan jurusan agar hasil seeding selalu sama.
 */
class StudyProgramSeeder extends Seeder
{
    private const BASE_MONTHLY_INSTALLMENT = [
        'karyawan' => 600000,
        'reguler' => 800000,
        'rpl' => 650000,
        'shift' => 700000,
    ];

    private const MONTHLY_STEP = 50000;

    private const MONTHLY_STEP_COUNT = 6;

    private const REGISTRATION_FEE = 100000;

    private const FIRST_PAYMENT_MONTHS = 2;

    // Harga coret pada program karyawan dan RPL, dalam persen dari harga bayar.
    private const LIST_PRICE_PERCENT = 125;

    private const SCHEDULES = [
        'karyawan' => [ClassSchedule::Malam, ClassSchedule::AkhirPekan],
        'reguler' => [ClassSchedule::Pagi],
        'rpl' => [ClassSchedule::Malam, ClassSchedule::AkhirPekan],
        'shift' => [ClassSchedule::Shift, ClassSchedule::Malam],
    ];

    private const METHODS = [
        'karyawan' => [LearningMethod::Blended, LearningMethod::Hybrid],
        'reguler' => [LearningMethod::TatapMuka],
        'rpl' => [LearningMethod::Blended],
        'shift' => [LearningMethod::Hybrid, LearningMethod::TatapMuka],
    ];

    private const VOCATIONAL_FORMS = ['politeknik', 'akademi'];

    private const SWASTA_PROGRAM_CYCLE = [
        ProgramType::Karyawan,
        ProgramType::Reguler,
        ProgramType::Rpl,
        ProgramType::Shift,
    ];

    public function run(): void
    {
        //
    }

    /**
     * @param  array<int, string>  $majorNames
     */
    public function seedForCampus(Campus $campus, array $majorNames): void
    {
        $campus->studyPrograms()->delete();

        Major::whereIn('name', $majorNames)->orderBy('name')->get()
            ->each(fn (Major $major, int $index) => $campus->studyPrograms()->create(
                $this->attributes($campus, $major, $index),
            ));
    }

    /**
     * @return array<string, mixed>
     */
    private function attributes(Campus $campus, Major $major, int $index): array
    {
        $type = $this->programType($campus, $index);
        $monthly = $this->monthlyInstallment($campus, $major, $type);

        return [
            'major_id' => $major->getKey(),
            'degree_level' => $this->degreeLevel($campus),
            'program_type' => $type,
            'accreditation' => $campus->accreditation,
            'registration_fee' => self::REGISTRATION_FEE,
            'first_payment' => $monthly * self::FIRST_PAYMENT_MONTHS,
            'monthly_installment' => $monthly,
            'original_monthly_installment' => $this->listPrice($type, $monthly),
            'schedules' => self::SCHEDULES[$type->value],
            'methods' => self::METHODS[$type->value],
        ];
    }

    private function programType(Campus $campus, int $index): ProgramType
    {
        if ($campus->type !== Campus::TYPE_SWASTA) {
            return ProgramType::Reguler;
        }

        return self::SWASTA_PROGRAM_CYCLE[$index % count(self::SWASTA_PROGRAM_CYCLE)];
    }

    private function degreeLevel(Campus $campus): DegreeLevel
    {
        return in_array($campus->form, self::VOCATIONAL_FORMS, true) ? DegreeLevel::D3 : DegreeLevel::S1;
    }

    private function monthlyInstallment(Campus $campus, Major $major, ProgramType $type): int
    {
        $variation = crc32($campus->slug.$major->slug) % self::MONTHLY_STEP_COUNT;

        return self::BASE_MONTHLY_INSTALLMENT[$type->value] + $variation * self::MONTHLY_STEP;
    }

    private function listPrice(ProgramType $type, int $monthly): ?int
    {
        if (! in_array($type, [ProgramType::Karyawan, ProgramType::Rpl], true)) {
            return null;
        }

        return intdiv($monthly * self::LIST_PRICE_PERCENT, 100);
    }
}
