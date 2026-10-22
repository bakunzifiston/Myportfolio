<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\SkillCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['Programming Languages', 'code-xml', 'Languages used to build applications and services.',
                ['PHP', 'Python', 'JavaScript', 'Java']],
            ['Frontend Development', 'layout-template', 'Interfaces that are responsive, accessible and maintainable.',
                ['HTML5', 'CSS3', 'Tailwind CSS', 'JavaScript', 'Next.js']],
            ['Backend Development', 'server', 'Application logic, services and APIs.',
                ['Laravel', 'PHP', 'Node.js', 'REST API development']],
            ['Databases', 'database', 'Relational and document data stores.',
                ['MySQL', 'PostgreSQL', 'MongoDB']],
            ['Software Engineering & Architecture', 'network', 'Designing systems that are secure, scalable and easy to evolve.',
                [
                    'Full-stack application development', 'System design', 'Database design',
                    'API development and integration', 'Authentication and authorization',
                    'Information management systems', 'Responsive web development',
                    'Application architecture', 'Digital transformation solutions',
                ]],
        ];

        foreach ($categories as $i => [$name, $icon, $description, $skills]) {
            $category = SkillCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name, 'icon' => $icon, 'description' => $description, 'sort_order' => $i + 1],
            );

            foreach ($skills as $j => $skill) {
                Skill::firstOrCreate(
                    ['skill_category_id' => $category->id, 'name' => $skill],
                    ['is_visible' => true, 'sort_order' => $j + 1],
                );
            }
        }
    }
}
