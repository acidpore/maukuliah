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
            TestimonialSeeder::class,
            CampusPhotoSeeder::class,
            UserSeeder::class,
            FaqSeeder::class,
            ArticleSeeder::class,
            DemoSeeder::class,
        ]);
    }
}
