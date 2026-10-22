<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SkillCategoryRequest;
use App\Models\SkillCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class SkillCategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.skill-categories.index', [
            'categories' => SkillCategory::with('skills')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.skill-categories.form', ['category' => new SkillCategory]);
    }

    public function store(SkillCategoryRequest $request): RedirectResponse
    {
        $this->save(new SkillCategory, $request);

        return redirect()->route('admin.skill-categories.index')->with('status', 'Skill category created.');
    }

    public function edit(SkillCategory $skillCategory): View
    {
        return view('admin.skill-categories.form', ['category' => $skillCategory->load('skills')]);
    }

    public function update(SkillCategoryRequest $request, SkillCategory $skillCategory): RedirectResponse
    {
        $this->save($skillCategory, $request);

        return redirect()->route('admin.skill-categories.index')->with('status', 'Skill category updated.');
    }

    public function destroy(SkillCategory $skillCategory): RedirectResponse
    {
        $skillCategory->delete();

        return redirect()->route('admin.skill-categories.index')->with('status', 'Skill category deleted.');
    }

    /** Saves the category and syncs its skills from the "one per line" list, preserving the given order. */
    private function save(SkillCategory $category, SkillCategoryRequest $request): void
    {
        DB::transaction(function () use ($category, $request) {
            $category->fill($request->safe()->except('skills'));
            $category->sort_order ??= 0;
            $category->save();

            $names = collect($request->validated('skills') ?? []);
            $category->skills()->whereNotIn('name', $names->all())->delete();

            $names->each(fn ($name, $i) => $category->skills()->updateOrCreate(
                ['name' => $name],
                ['sort_order' => $i + 1, 'is_visible' => true],
            ));
        });
    }
}
