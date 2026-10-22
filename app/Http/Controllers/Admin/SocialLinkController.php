<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SocialLinkRequest;
use App\Models\SocialLink;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SocialLinkController extends Controller
{
    public function index(): View
    {
        return view('admin.social-links.index', ['links' => SocialLink::orderBy('sort_order')->get()]);
    }

    public function create(): View
    {
        return view('admin.social-links.form', ['link' => new SocialLink(['is_visible' => true])]);
    }

    public function store(SocialLinkRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= 0;
        SocialLink::create($data);

        return redirect()->route('admin.social-links.index')->with('status', 'Link created.');
    }

    public function edit(SocialLink $socialLink): View
    {
        return view('admin.social-links.form', ['link' => $socialLink]);
    }

    public function update(SocialLinkRequest $request, SocialLink $socialLink): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] ??= 0;
        $socialLink->update($data);

        return redirect()->route('admin.social-links.index')->with('status', 'Link updated.');
    }

    public function destroy(SocialLink $socialLink): RedirectResponse
    {
        $socialLink->delete();

        return redirect()->route('admin.social-links.index')->with('status', 'Link deleted.');
    }
}
