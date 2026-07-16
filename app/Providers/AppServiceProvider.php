<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Failed::class,
            \App\Listeners\LogFailedLogin::class
        );

        Gate::define('viewPulse', function (User $user) {
            return $user->hasFullAccess();
        });

        \Illuminate\Support\Facades\Vite::usePreloadTagAttributes(function ($src) {
            if (str_ends_with($src, '.css')) {
                return false;
            }
            return [];
        });
    }
}
