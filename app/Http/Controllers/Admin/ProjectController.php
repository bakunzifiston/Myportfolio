<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use App\Models\ProjectCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::query()->with('category')->orderBy('sort_order')->orderBy('title')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.projects.form', [
            'project' => new Project(['status' => Project::STATUS_DRAFT, 'sort_order' => Project::max('sort_order') + 1]),
            'categories' => ProjectCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $project = new Project;
        $this->save($project, $request);

        return redirect()->route('admin.projects.edit', $project)->with('status', 'Project created.');
    }

    public function edit(Project $project): View
    {
        return view('admin.projects.form', [
            'project' => $project,
            'categories' => ProjectCategory::orderBy('sort_order')->get(),
        ]);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $this->save($project, $request);

        return redirect()->route('admin.projects.edit', $project)->with('status', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $disk = Storage::disk('public');
        $disk->delete(array_filter([$project->thumbnail, ...($project->screenshots ?? [])]));
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', 'Project deleted.');
    }

    private function save(Project $project, ProjectRequest $request): void
    {
        $disk = Storage::disk('public');
        $data = $request->safe()->except(['thumbnail_upload', 'remove_thumbnail', 'screenshot_uploads', 'remove_screenshots']);
        $data['sort_order'] ??= 0;

        if ($request->boolean('remove_thumbnail') && $project->thumbnail) {
            $disk->delete($project->thumbnail);
            $data['thumbnail'] = null;
        }

        if ($request->hasFile('thumbnail_upload')) {
            if ($project->thumbnail) {
                $disk->delete($project->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail_upload')->store('projects', 'public');
        }

        $screenshots = collect($project->screenshots ?? []);
        $remove = collect($request->input('remove_screenshots', []))->intersect($screenshots);
        $disk->delete($remove->all());
        $screenshots = $screenshots->diff($remove);

        foreach ($request->file('screenshot_uploads', []) as $file) {
            $screenshots->push($file->store('projects/screenshots', 'public'));
        }

        $data['screenshots'] = $screenshots->values()->all() ?: null;

        $project->fill($data)->save();
    }
}
