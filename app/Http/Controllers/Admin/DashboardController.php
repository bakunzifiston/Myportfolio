<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certification;
use App\Models\ContactMessage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Published projects', 'value' => Project::published()->count(), 'route' => 'admin.projects.index'],
                ['label' => 'Draft projects', 'value' => Project::where('status', Project::STATUS_DRAFT)->count(), 'route' => 'admin.projects.index'],
                ['label' => 'Visible skills', 'value' => Skill::where('is_visible', true)->count(), 'route' => 'admin.skill-categories.index'],
                ['label' => 'Published experience', 'value' => Experience::public()->count(), 'route' => 'admin.experiences.index'],
                ['label' => 'Education entries', 'value' => Education::count(), 'route' => 'admin.education.index'],
                ['label' => 'Certifications', 'value' => Certification::count(), 'route' => 'admin.certifications.index'],
            ],
            'unreadMessages' => ContactMessage::whereNull('read_at')->count(),
            'recentMessages' => ContactMessage::latest()->limit(5)->get(),
            'incompleteProjects' => Project::query()->ordered()->get()->reject->hasCaseStudy(),
        ]);
    }
}
