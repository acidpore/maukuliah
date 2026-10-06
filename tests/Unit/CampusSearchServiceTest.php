<?php

namespace Tests\Unit;

use App\Enums\ClassSchedule;
use App\Enums\LearningMethod;
use App\Enums\ProgramType;
use App\Models\Campus;
use App\Models\StudyProgram;
use App\Services\CampusSearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampusSearchServiceTest extends TestCase
{
    use RefreshDatabase;

    private CampusSearchService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new CampusSearchService;
    }

    private function names(array $filters, ?string $sort = null): array
    {
        return $this->service->search($filters, null, $sort)->pluck('name')->all();
    }

    public function test_unverified_campuses_are_hidden(): void
    {
        Campus::factory()->create(['name' => 'Kampus Tampil']);
        Campus::factory()->unverified()->create(['name' => 'Kampus Draft']);

        $this->assertSame(['Kampus Tampil'], $this->names([]));
    }

    public function test_filters_by_program_type(): void
    {
        $employee = Campus::factory()->create(['name' => 'Kampus Karyawan']);
        $regular = Campus::factory()->create(['name' => 'Kampus Reguler']);
        StudyProgram::factory()->create(['campus_id' => $employee->id, 'program_type' => ProgramType::Karyawan]);
        StudyProgram::factory()->create(['campus_id' => $regular->id, 'program_type' => ProgramType::Reguler]);

        $this->assertSame(['Kampus Karyawan'], $this->names(['program_type' => 'karyawan']));
    }

    public function test_filters_by_schedule_and_method(): void
    {
        $match = Campus::factory()->create(['name' => 'Kampus Malam Hybrid']);
        $other = Campus::factory()->create(['name' => 'Kampus Pagi']);
        StudyProgram::factory()->create([
            'campus_id' => $match->id,
            'schedules' => [ClassSchedule::Malam],
            'methods' => [LearningMethod::Hybrid],
        ]);
        StudyProgram::factory()->create(['campus_id' => $other->id]);

        $this->assertSame(['Kampus Malam Hybrid'], $this->names(['schedule' => 'malam']));
        $this->assertSame(['Kampus Malam Hybrid'], $this->names(['method' => 'hybrid']));
        $this->assertSame([], $this->names(['schedule' => 'malam', 'method' => 'tatap-muka']));
    }

    public function test_program_filters_must_match_the_same_program(): void
    {
        $campus = Campus::factory()->create();
        StudyProgram::factory()->create([
            'campus_id' => $campus->id,
            'program_type' => ProgramType::Karyawan,
            'schedules' => [ClassSchedule::Malam],
        ]);
        StudyProgram::factory()->create([
            'campus_id' => $campus->id,
            'program_type' => ProgramType::Reguler,
            'schedules' => [ClassSchedule::Pagi],
        ]);

        $this->assertSame([], $this->names(['program_type' => 'karyawan', 'schedule' => 'pagi']));
    }

    public function test_filters_by_monthly_fee_range(): void
    {
        $cheap = Campus::factory()->create(['name' => 'Murah']);
        $pricey = Campus::factory()->create(['name' => 'Mahal']);
        StudyProgram::factory()->create(['campus_id' => $cheap->id, 'monthly_installment' => 500000]);
        StudyProgram::factory()->create(['campus_id' => $pricey->id, 'monthly_installment' => 2000000]);

        $this->assertSame(['Murah'], $this->names(['fee_max' => 1000000]));
        $this->assertSame(['Mahal'], $this->names(['fee_min' => 1000000]));
    }

    public function test_filters_by_region_matching_province_or_city(): void
    {
        Campus::factory()->create(['name' => 'Di Bogor', 'city' => 'Bogor', 'province' => 'Jawa Barat']);
        Campus::factory()->create(['name' => 'Di Medan', 'city' => 'Medan', 'province' => 'Sumatera Utara']);

        $this->assertSame(['Di Bogor'], $this->names(['region' => 'Jawa Barat']));
        $this->assertSame(['Di Medan'], $this->names(['region' => 'Medan']));
    }

    public function test_sorts_by_cheapest_with_unpriced_campuses_last(): void
    {
        $noPrice = Campus::factory()->create(['name' => 'A Tanpa Harga']);
        $cheap = Campus::factory()->create(['name' => 'C Murah']);
        $pricey = Campus::factory()->create(['name' => 'B Mahal']);
        StudyProgram::factory()->create(['campus_id' => $cheap->id, 'monthly_installment' => 400000]);
        StudyProgram::factory()->create(['campus_id' => $pricey->id, 'monthly_installment' => 900000]);

        $this->assertSame(
            ['C Murah', 'B Mahal', $noPrice->name],
            $this->names([], CampusSearchService::SORT_CHEAPEST),
        );
    }

    public function test_exposes_minimum_monthly_installment(): void
    {
        $campus = Campus::factory()->create();
        StudyProgram::factory()->create(['campus_id' => $campus->id, 'monthly_installment' => 700000]);
        StudyProgram::factory()->create(['campus_id' => $campus->id, 'monthly_installment' => 450000]);

        $result = $this->service->search([], null, null)->first();

        $this->assertSame(450000, (int) $result->min_monthly_installment);
    }

    public function test_resolves_region_slug_to_name(): void
    {
        Campus::factory()->create(['city' => 'Kota Bogor', 'province' => 'Jawa Barat']);

        $this->assertSame('Jawa Barat', $this->service->resolveRegion('jawa-barat'));
        $this->assertSame('Kota Bogor', $this->service->resolveRegion('kota-bogor'));
        $this->assertNull($this->service->resolveRegion('tidak-ada'));
    }
}
