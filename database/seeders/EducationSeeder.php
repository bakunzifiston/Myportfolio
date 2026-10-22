<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;

/**
 * Institutions, dates and completion status are unconfirmed, so every entry is "pursued" and
 * is presented publicly as studies rather than as an awarded degree.
 */
class EducationSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            ['level' => 'master', 'field_of_study' => null, 'country' => null, 'sort_order' => 1],
            ['level' => 'bachelor', 'field_of_study' => 'Software Engineering', 'country' => 'Turkish Republic of Northern Cyprus', 'sort_order' => 2],
            ['level' => 'bachelor', 'field_of_study' => 'Computer Science', 'country' => 'China', 'sort_order' => 3],
        ];

        foreach ($entries as $entry) {
            Education::firstOrCreate(
                ['level' => $entry['level'], 'field_of_study' => $entry['field_of_study']],
                $entry + ['status' => 'pursued', 'is_published' => true],
            );
        }
    }
}
