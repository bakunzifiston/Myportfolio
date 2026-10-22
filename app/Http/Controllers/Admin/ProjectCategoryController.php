<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectCategoryRequest;
use App\Models\ProjectCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.project-categories.index', [
            'categories' => ProjectCategory::withCount('projects')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.project-categories.form', ['category' => new ProjectCategory]);
    }

    public function store(ProjectCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= 0;
        ProjectCategory::create($data);

        return redirect()->route('admin.project-categories.index')->with('status', 'Category created.');
    }

    public function edit(ProjectCategory $projectCategory): View
    {
        return view('admin.project-categories.form', ['category' => $projectCategory]);
    }

    public function update(ProjectCategoryRequest $request, ProjectCategory $projectCategory): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= 0;
        $projectCategory->update($data);

        return redirect()->route('admin.project-categories.index')->with('status', 'Category updated.');
    }

    public function destroy(ProjectCategory $projectCategory): RedirectResponse
    {
        // Projects keep existing; the foreign key sets their category to null.
        $projectCategory->delete();

        return redirect()->route('admin.project-categories.index')->with('status', 'Category deleted.');
    }
}
