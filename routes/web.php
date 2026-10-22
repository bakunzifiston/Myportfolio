<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
| Public site
*/
Route::get('/', HomeController::class)->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');
Route::get('/resume', [ResumeController::class, 'show'])->name('resume.show');
Route::get('/resume/download', [ResumeController::class, 'download'])->middleware('throttle:20,1')->name('resume.download');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:contact')->name('contact.store');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');

/*
| Administration
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'store'])->middleware('throttle:10,1')->name('login.store');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'destroy'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');

        Route::resource('projects', Admin\ProjectController::class)->except('show');
        Route::resource('project-categories', Admin\ProjectCategoryController::class)->except('show');
        Route::resource('skill-categories', Admin\SkillCategoryController::class)->except('show');
        Route::resource('experiences', Admin\ExperienceController::class)->except('show');
        Route::resource('education', Admin\EducationController::class)->except('show')->parameters(['education' => 'education']);
        Route::resource('certifications', Admin\CertificationController::class)->except('show');
        Route::resource('social-links', Admin\SocialLinkController::class)->except('show');
        Route::resource('messages', Admin\ContactMessageController::class)->only(['index', 'show', 'destroy']);

        Route::get('settings', [Admin\SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
    });
});
