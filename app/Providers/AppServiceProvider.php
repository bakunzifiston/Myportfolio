<?php

namespace App\Providers;

use App\Support\Portfolio;
use App\Support\Settings;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('contact', fn (Request $request) => [
            Limit::perMinute(3)->by($request->ip()),
            Limit::perHour(10)->by($request->ip()),
        ]);

        // Profile settings and social links are needed by every public page.
        View::composer(['components.layouts.site', 'home', 'projects.*', 'resume.*', 'errors.*'], function ($view) {
            $view->with('settings', Settings::all())
                ->with('socialLinks', once(fn () => rescue(fn () => Portfolio::socialLinks(), collect(), report: false)));
        });
    }
}
