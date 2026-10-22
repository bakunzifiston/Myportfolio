<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsRequest;
use App\Models\SiteSetting;
use App\Support\Settings;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'groups' => config('portfolio.settings'),
            'values' => Settings::all(),
        ]);
    }

    public function update(SettingsRequest $request): RedirectResponse
    {
        $disk = Storage::disk('public');

        foreach (Settings::definitions() as $key => $def) {
            if ($def['type'] === 'file') {
                $current = SiteSetting::where('key', $key)->value('value');

                if ($request->boolean('remove_'.$key) || $request->hasFile($key)) {
                    if ($current) {
                        $disk->delete($current);
                    }
                    $path = $request->hasFile($key) ? $request->file($key)->store('settings', 'public') : null;
                    SiteSetting::updateOrCreate(['key' => $key], ['value' => $path]);
                }

                continue;
            }

            $value = $request->input($key);
            // Store an empty string rather than null so that a deliberately cleared field stays cleared
            // instead of falling back to its default.
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value === null ? '' : trim($value)]);
        }

        Settings::flush();

        return redirect()->route('admin.settings.edit')->with('status', 'Settings saved.');
    }
}
