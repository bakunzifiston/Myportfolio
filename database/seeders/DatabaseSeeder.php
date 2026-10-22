<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed initial portfolio content. Every seeder is idempotent: it only creates records that
     * do not exist yet, so running it again in production never overwrites edited content.
     */
    public function run(): void
    {
        $this->call([
            SocialLinkSeeder::class,
            SkillSeeder::class,
            ProjectSeeder::class,
            ExperienceSeeder::class,
            EducationSeeder::class,
            CertificationSeeder::class,
        ]);
    }
}
