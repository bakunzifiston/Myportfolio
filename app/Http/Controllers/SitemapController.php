<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $projects = Project::query()->published()->ordered()->get(['slug', 'updated_at']);

        return response()
            ->view('sitemap', compact('projects'))
            ->header('Content-Type', 'application/xml');
    }
}
