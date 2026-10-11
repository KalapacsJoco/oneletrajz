<?php

namespace App\Providers;

use App\Models\Discipline;
use App\Models\Profile;
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
        View::composer(['layouts.site', 'pages.*'], function ($view) {
            $view->with([
                'profile' => Profile::current(),
                'navDisciplines' => Discipline::where('visible', true)->orderBy('sort')->get(),
            ]);
        });
    }
}
