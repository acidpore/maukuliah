<?php

namespace Tests\Feature;

use App\Models\Affiliate;
use App\Models\Commission;
use App\Models\Favorite;
use App\Models\Lead;
use App\Models\TestResult;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_data_is_populated(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(18, Lead::count());
        $this->assertSame(1, Affiliate::count());
        $this->assertSame(5, Affiliate::first()->referrals()->count());
        $this->assertSame(3, Commission::count());
        $this->assertSame(3, TestResult::count());
        $this->assertSame(6, Favorite::count());
        $this->assertSame(4, Lead::distinct()->count('status'));
    }

    public function test_demo_seeder_is_idempotent(): void
    {
        $this->seed(DatabaseSeeder::class);
        $this->seed(DatabaseSeeder::class);

        $this->assertSame(18, Lead::count());
        $this->assertSame(1, Affiliate::count());
        $this->assertSame(3, Commission::count());
        $this->assertSame(3, TestResult::count());
        $this->assertSame(6, Favorite::count());
    }
}
