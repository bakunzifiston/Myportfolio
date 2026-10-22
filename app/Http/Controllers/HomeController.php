<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Support\Portfolio;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $projects = Portfolio::publishedProjects();
        $featured = $projects->where('is_featured', true)->take(6);

        return view('home', [
            'featuredProjects' => $featured->isNotEmpty() ? $featured : $projects->take(6),
            'projectCount' => $projects->count(),
            'projects' => $projects,
            'skillCategories' => Portfolio::skillCategories(),
            'experiences' => Portfolio::experiences(),
            'education' => Portfolio::education(),
            'certifications' => Portfolio::certifications(),
            'contactToken' => ContactRequest::freshToken(),
        ]);
    }
}
