<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ExperienceRequest;
use App\Models\Experience;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ExperienceController extends Controller
{
    public function index(): View
    {
        return view('admin.experiences.index', [
            'experiences' => Experience::with('project')->orderBy('type')->orderBy('sort_order')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.experiences.form', [
            'experience' => new Experience(['type' => request('type') === 'project' ? 'project' : 'employment']),
            'projects' => Project::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function store(ExperienceRequest $request): RedirectResponse
    {
        Experience::create($this->payload($request));

        return redirect()->route('admin.experiences.index')->with('status', 'Experience entry created.');
    }

    public function edit(Experience $experience): View
    {
        return view('admin.experiences.form', [
            'experience' => $experience,
            'projects' => Project::orderBy('title')->get(['id', 'title']),
        ]);
    }

    public function update(ExperienceRequest $request, Experience $experience): RedirectResponse
    {
        $experience->update($this->payload($request));

        return redirect()->route('admin.experiences.index')->with('status', 'Experience entry updated.');
    }

    public function destroy(Experience $experience): RedirectResponse
    {
        $experience->delete();

        return redirect()->route('admin.experiences.index')->with('status', 'Experience entry deleted.');
    }

    private function payload(ExperienceRequest $request): array
    {
        $data = $request->validated();
        $data['sort_order'] ??= 0;

        return $data;
    }
}
