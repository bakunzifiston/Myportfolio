<?php

namespace App\Support;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\SkillCategory;
use App\Models\SocialLink;
use Illuminate\Support\Collection;

/**
 * Query helpers shared by the home page, the CV page and the PDF so they always agree.
 */
class Portfolio
{
    public static function skillCategories(): Collection
    {
        return SkillCategory::query()
            ->with('visibleSkills')
            ->orderBy('sort_order')
            ->get()
            ->filter(fn ($category) => $category->visibleSkills->isNotEmpty())
            ->values();
    }

    /** @return array{employment: Collection, project: Collection} */
    public static function experiences(): array
    {
        $all = Experience::query()->public()->with('project')->get();

        return [
            'employment' => $all->where('type', 'employment')->values(),
            'project' => $all->where('type', 'project')->values(),
        ];
    }

    public static function education(): Collection
    {
        return Education::query()->public()->get();
    }

    public static function certifications(): Collection
    {
        return Certification::query()->public()->get();
    }

    public static function publishedProjects(): Collection
    {
        return Project::query()->published()->with('category')->ordered()->get();
    }

    public static function socialLinks(): Collection
    {
        return SocialLink::query()->public()->get();
    }
}
