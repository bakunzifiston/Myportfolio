<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Project;
use Illuminate\Database\Seeder;

/**
 * No roles or employers have been confirmed yet, so this only prepares unpublished
 * project-contribution placeholders to be completed in the admin area.
 */
class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $slugs = ['ihuzo', 'msmes-go-digital', 'koralink'];

        foreach (Project::whereIn('slug', $slugs)->get() as $i => $project) {
            Experience::firstOrCreate(
                ['type' => 'project', 'project_id' => $project->id],
                ['is_published' => false, 'sort_order' => $i + 1],
            );
        }
    }
}
