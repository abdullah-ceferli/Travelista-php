<?php

namespace App\Providers;

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
        View::composer('*', function ($view) {
            $errors = session('errors');
            $view->with('logged_in_user', auth()->user());
            $view->with('error', session('error') ?? $errors?->first());
            $view->with('success', session('success'));
        });
    }
}
