<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds the project list with only the facts that have been confirmed. Roles, technologies,
 * URLs, screenshots and results are intentionally left empty until they are provided.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect(['Language Technology', 'Digital Transformation', 'Digital Development', 'E-Learning'])
            ->mapWithKeys(fn ($name, $i) => [$name => ProjectCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'sort_order' => $i + 1],
            )->id]);

        $projects = [
            [
                'title' => 'NileLingua',
                'category' => 'Language Technology',
                'short_description' => 'African language services and human-in-the-loop AI language data, helping global organisations engage with African audiences.',
                'overview' => "Nile Lingua Lab (NLL) is a Kigali-based provider of African language services that bridges linguistic divides across East Africa and beyond. It combines regional language expertise with modern language technology to help global organisations communicate effectively with African audiences.\n\nIts services span enterprise translation, software and multimedia localization, and specialized language datasets for AI models, with every output reviewed by people who know the language and culture, from Kinyarwanda and Swahili to Dinka and Sango.",
                'key_features' => [
                    'Enterprise translation for African languages',
                    'Software and multimedia localization',
                    'Specialized language datasets for training AI models',
                    'Speech data collection and annotation',
                    'Human-in-the-loop review for quality assurance',
                    'Coverage including Kinyarwanda, Swahili, Dinka and Sango',
                ],
                'is_featured' => true,
            ],
            [
                'title' => 'IHUZO',
                'category' => 'Digital Transformation',
                'short_description' => 'A digital initiative centred on assessing the digital maturity of businesses and supporting their digital transformation.',
                'overview' => 'IHUZO is a digital initiative associated with business digital maturity assessment and digital transformation, helping organisations understand where they stand digitally and how they can progress.',
                'is_featured' => true,
            ],
            [
                'title' => 'MSMEs Go Digital',
                'category' => 'Digital Transformation',
                'short_description' => 'A project supporting digital transformation among micro, small, and medium enterprises.',
                'overview' => 'MSMEs Go Digital is a project focused on supporting micro, small, and medium enterprises (MSMEs) as they adopt digital tools and practices.',
                'is_featured' => true,
            ],
            [
                'title' => 'KoraLink',
                'category' => 'E-Learning',
                'short_description' => 'A digital learning platform in the e-learning space.',
                'overview' => null,
                'is_featured' => true,
            ],
            [
                'title' => 'RDEP Phase II',
                'category' => 'Digital Development',
                'short_description' => 'A project associated with digital development.',
                'overview' => null,
                'is_featured' => true,
            ],
            ['title' => 'DBI'],
            ['title' => 'RwandaMart'],
            ['title' => 'VIBE'],
            // Working title: kept as a draft (hidden publicly) until the official name is confirmed.
            ['title' => 'Data Access and Relation Programme', 'status' => Project::STATUS_DRAFT],
        ];

        foreach ($projects as $i => $data) {
            $slug = Str::slug($data['title']);

            Project::firstOrCreate(['slug' => $slug], [
                'title' => $data['title'],
                'project_category_id' => isset($data['category']) ? $categories[$data['category']] : null,
                'short_description' => $data['short_description'] ?? null,
                'overview' => $data['overview'] ?? null,
                'key_features' => $data['key_features'] ?? null,
                'is_featured' => $data['is_featured'] ?? false,
                'status' => $data['status'] ?? Project::STATUS_PUBLISHED,
                'sort_order' => $i + 1,
            ]);
        }
    }
}
