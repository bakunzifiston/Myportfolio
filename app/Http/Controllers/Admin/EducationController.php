<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EducationRequest;
use App\Models\Education;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EducationController extends Controller
{
    public function index(): View
    {
        return view('admin.education.index', ['entries' => Education::orderBy('sort_order')->get()]);
    }

    public function create(): View
    {
        return view('admin.education.form', ['entry' => new Education(['status' => 'pursued', 'is_published' => true])]);
    }

    public function store(EducationRequest $request): RedirectResponse
    {
        Education::create($this->payload($request));

        return redirect()->route('admin.education.index')->with('status', 'Education entry created.');
    }

    public function edit(Education $education): View
    {
        return view('admin.education.form', ['entry' => $education]);
    }

    public function update(EducationRequest $request, Education $education): RedirectResponse
    {
        $education->update($this->payload($request));

        return redirect()->route('admin.education.index')->with('status', 'Education entry updated.');
    }

    public function destroy(Education $education): RedirectResponse
    {
        $education->delete();

        return redirect()->route('admin.education.index')->with('status', 'Education entry deleted.');
    }

    private function payload(EducationRequest $request): array
    {
        $data = $request->safe()->except('confirm_awarded');
        $data['sort_order'] ??= 0;

        return $data;
    }
}
