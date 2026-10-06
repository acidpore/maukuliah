<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MajorSeeder::class,
            CampusSeeder::class,
            CareerSeeder::class,
            ScholarshipSeeder::class,
            BrochureSeeder::class,
            UserSeeder::class,
            FaqSeeder::class,
        ]);
    }
}
