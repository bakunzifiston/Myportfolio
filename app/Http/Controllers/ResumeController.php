<?php

namespace App\Http\Controllers;

use App\Support\Portfolio;
use App\Support\Settings;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ResumeController extends Controller
{
    public function show(): View
    {
        return view('resume.show', $this->data());
    }

    /**
     * Serves an uploaded CV when one is configured; otherwise renders the CV from the database as a PDF.
     */
    public function download(): Response
    {
        $filename = Str::of(Settings::get('full_name', 'Resume'))->ascii()->replaceMatches('/[^A-Za-z0-9]+/', '-')->trim('-').'-CV.pdf';
        $uploaded = Settings::get('resume_file');

        if ($uploaded && Storage::disk('public')->exists($uploaded)) {
            return Storage::disk('public')->download($uploaded, $filename);
        }

        return Pdf::loadView('resume.pdf', $this->data())
            ->setPaper('a4')
            ->setOption(['defaultFont' => 'DejaVu Sans', 'isRemoteEnabled' => false, 'isFontSubsettingEnabled' => true])
            ->download($filename);
    }

    /** @return array<string, mixed> */
    private function data(): array
    {
        // Projects with a description are listed in full (featured first); the rest are named on one line.
        [$described, $others] = Portfolio::publishedProjects()->partition(fn ($p) => filled($p->short_description));
        $selected = $described->sortByDesc('is_featured')->take(6)->values();

        return [
            'skillCategories' => Portfolio::skillCategories(),
            'experiences' => Portfolio::experiences(),
            'projects' => $selected,
            'otherProjects' => $described->diff($selected)->concat($others)->pluck('title'),
            'education' => Portfolio::education(),
            'certifications' => Portfolio::certifications(),
        ];
    }
}
