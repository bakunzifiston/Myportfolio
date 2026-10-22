<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CertificationRequest;
use App\Models\Certification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CertificationController extends Controller
{
    public function index(): View
    {
        return view('admin.certifications.index', ['certifications' => Certification::orderBy('sort_order')->get()]);
    }

    public function create(): View
    {
        return view('admin.certifications.form', ['certification' => new Certification(['is_published' => true])]);
    }

    public function store(CertificationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= 0;
        Certification::create($data);

        return redirect()->route('admin.certifications.index')->with('status', 'Certification created.');
    }

    public function edit(Certification $certification): View
    {
        return view('admin.certifications.form', compact('certification'));
    }

    public function update(CertificationRequest $request, Certification $certification): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= 0;
        $certification->update($data);

        return redirect()->route('admin.certifications.index')->with('status', 'Certification updated.');
    }

    public function destroy(Certification $certification): RedirectResponse
    {
        $certification->delete();

        return redirect()->route('admin.certifications.index')->with('status', 'Certification deleted.');
    }
}
