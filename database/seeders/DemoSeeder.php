<?php

namespace Database\Seeders;

use App\Enums\AffiliateCategory;
use App\Enums\CommissionStatus;
use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use App\Enums\ReferralStatus;
use App\Enums\UserRole;
use App\Models\Affiliate;
use App\Models\AffiliateReferral;
use App\Models\Application;
use App\Models\Campus;
use App\Models\Career;
use App\Models\Commission;
use App\Models\Major;
use App\Models\Scholarship;
use App\Models\TestResult;
use App\Models\User;
use App\Services\AffiliateService;
use App\Services\ApplicationService;
use App\Services\FavoriteService;
use App\Services\LeadService;
use App\Services\PotentialTestRegistry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Data demo agar dasbor admin, afiliasi, dan akun siswa terlihat terisi.
 * Idempoten: dijalankan ulang tidak menggandakan data.
 */
class DemoSeeder extends Seeder
{
    private const DEMO_STUDENT_EMAIL = 'siswa@maukuliah.test';

    private const OTHER_STUDENTS = [
        ['Anisa Rahmawati', 'anisa.rahmawati@example.test'],
        ['Bagas Setiawan', 'bagas.setiawan@example.test'],
        ['Citra Lestari', 'citra.lestari@example.test'],
        ['Dimas Aditya', 'dimas.aditya@example.test'],
        ['Eka Wulandari', 'eka.wulandari@example.test'],
        ['Farhan Hakim', 'farhan.hakim@example.test'],
    ];

    /**
     * Lead non-pendaftaran: [indeks siswa lain, indeks kampus, sumber, status, hari lalu].
     */
    private const ENGAGEMENT_LEADS = [
        [0, 0, LeadSource::Brochure, LeadStatus::Contacted, 6],
        [1, 1, LeadSource::Brochure, LeadStatus::New, 3],
        [2, 2, LeadSource::Favorite, LeadStatus::New, 1],
    ];

    private const TEST_TYPES = [
        'riasec' => TestResult::TYPE_RIASEC,
        'learning-style' => TestResult::TYPE_LEARNING_STYLE,
        'mbti' => TestResult::TYPE_MBTI,
    ];

    public function run(): void
    {
        $password = env('SEED_USER_PASSWORD') ?: Str::random(24);
        $demoStudent = $this->seedStudent('Siswa Demo', self::DEMO_STUDENT_EMAIL, $password);
        $others = $this->seedOtherStudents($password);
        $campuses = Campus::verified()->orderBy('id')->get();

        $affiliate = app(AffiliateService::class)->register($demoStudent, AffiliateCategory::Mahasiswa);

        $this->seedApplications($campuses, $affiliate);
        $this->seedEngagementLeads($campuses, $others);
        $this->seedTestResults($demoStudent);
        $this->seedFavorites($demoStudent, $campuses);
    }

    private function seedStudent(string $name, string $email, string $password): User
    {
        return User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => $password, 'role' => UserRole::Student],
        );
    }

    /**
     * @return Collection<int, User>
     */
    private function seedOtherStudents(string $password): Collection
    {
        return collect(self::OTHER_STUDENTS)
            ->map(fn (array $student) => $this->seedStudent($student[0], $student[1], $password));
    }

    /**
     * @param  Collection<int, Campus>  $campuses
     */
    private function seedApplications(Collection $campuses, Affiliate $affiliate): void
    {
        foreach (require __DIR__.'/data/demo_applicants.php' as $row) {
            [$name, $email, $whatsapp, $education, $region, $program, $schedule, $source, $campusIndex, $daysAgo, $status, $referralState, $commissionState] = $row;
            $campus = $campuses[$campusIndex % $campuses->count()];

            if (Application::where('email', $email)->where('campus_id', $campus->getKey())->exists()) {
                continue;
            }

            $application = app(ApplicationService::class)->submit([
                'campus_id' => $campus->getKey(),
                'major_id' => $campus->studyPrograms()->orderBy('id')->value('major_id'),
                'full_name' => $name,
                'email' => $email,
                'whatsapp' => $whatsapp,
                'last_education' => $education,
                'region' => $region,
                'program_type' => $program,
                'schedule' => $schedule,
                'source_info' => $source,
                'accepted_terms' => true,
            ], $referralState === null ? null : $affiliate->code);

            $this->backdate($application, LeadStatus::from($status), $daysAgo);
            $this->settleReferral($application->referral, $referralState, $commissionState);
        }
    }

    /**
     * Tanggal mundur dan status dipasang langsung agar dasbor punya riwayat 30 hari.
     */
    private function backdate(Application $application, LeadStatus $status, int $daysAgo): void
    {
        $createdAt = now()->subDays($daysAgo);

        $application->forceFill(['status' => $status, 'created_at' => $createdAt])->saveQuietly();
        $application->lead->forceFill(['status' => $status, 'created_at' => $createdAt])->saveQuietly();
        $application->referral?->forceFill(['created_at' => $createdAt])->saveQuietly();
    }

    private function settleReferral(?AffiliateReferral $referral, ?string $referralState, ?string $commissionState): void
    {
        if ($referral === null) {
            return;
        }

        if ($referralState === ReferralStatus::Rejected->value) {
            $referral->update(['status' => ReferralStatus::Rejected]);
        }

        if ($referralState === ReferralStatus::Paid->value) {
            app(AffiliateService::class)->markPaid($referral);
            $this->advanceCommission($referral->commission()->first(), $commissionState);
        }
    }

    private function advanceCommission(?Commission $commission, ?string $target): void
    {
        if ($commission === null) {
            return;
        }

        $affiliates = app(AffiliateService::class);

        if ($target !== CommissionStatus::Pending->value) {
            $affiliates->approveCommission($commission);
        }

        if ($target === CommissionStatus::Paid->value) {
            $affiliates->markCommissionPaid($commission->refresh());
        }
    }

    /**
     * @param  Collection<int, Campus>  $campuses
     * @param  Collection<int, User>  $students
     */
    private function seedEngagementLeads(Collection $campuses, Collection $students): void
    {
        foreach (self::ENGAGEMENT_LEADS as [$studentIndex, $campusIndex, $source, $status, $daysAgo]) {
            $lead = app(LeadService::class)->submit(
                $students[$studentIndex],
                $campuses[$campusIndex % $campuses->count()],
                $source,
            );

            $lead->forceFill(['status' => $status, 'created_at' => now()->subDays($daysAgo)])->saveQuietly();
        }
    }

    private function seedTestResults(User $student): void
    {
        $registry = app(PotentialTestRegistry::class);

        foreach (self::TEST_TYPES as $key => $type) {
            if ($student->testResults()->where('test_type', $type)->exists()) {
                continue;
            }

            $registry->submit($key, $student, $this->demoAnswers($key, $registry->questionCount($key)));
        }
    }

    /**
     * Pola berulang agar hasil tes sama setiap kali seeder dijalankan.
     *
     * @return array<int, int|string>
     */
    private function demoAnswers(string $key, int $count): array
    {
        return collect(range(0, $count - 1))
            ->mapWithKeys(fn (int $index) => [
                $index => $key === 'mbti'
                    ? ($index % 3 === 0 ? 'b' : 'a')
                    : (($index * 3) % 5) + 1,
            ])
            ->all();
    }

    /**
     * @param  Collection<int, Campus>  $campuses
     */
    private function seedFavorites(User $student, Collection $campuses): void
    {
        $favorites = app(FavoriteService::class);
        $items = collect([
            $campuses->get(0),
            $campuses->get(1),
            Major::orderBy('id')->first(),
            Major::orderBy('id')->skip(3)->first(),
            Career::orderBy('id')->first(),
            Scholarship::orderBy('id')->first(),
        ])->filter();

        foreach ($items as $item) {
            if (! $favorites->isFavorited($student, $item)) {
                $favorites->toggle($student, $item);
            }
        }
    }
}
