<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        // Rate limiter anti-brute-force pour l'authentification API (5 tentatives / minute par IP)
        RateLimiter::for('api-login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }
}
