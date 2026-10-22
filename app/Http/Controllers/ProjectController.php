<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $projects = Project::query()->published()->with('category')->ordered()->get();

        // Only offer filters for categories that actually contain published projects.
        $categories = ProjectCategory::query()
            ->whereIn('id', $projects->pluck('project_category_id')->filter()->unique())
            ->orderBy('sort_order')
            ->get();

        return view('projects.index', [
            'projects' => $projects,
            'categories' => $categories,
            'hasUncategorised' => $projects->contains(fn ($p) => $p->project_category_id === null),
            'activeCategory' => (string) $request->query('category', 'all'),
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->isPublished(), 404);

        $project->load('category');

        $related = Project::query()
            ->published()
            ->whereKeyNot($project->id)
            ->when($project->project_category_id, fn ($q) => $q->orderByRaw('project_category_id = ? desc', [$project->project_category_id]))
            ->ordered()
            ->limit(3)
            ->get();

        return view('projects.show', compact('project', 'related'));
    }
}
