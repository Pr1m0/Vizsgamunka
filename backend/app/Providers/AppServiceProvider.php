<?php

namespace App\Providers;

use App\Models\Game;
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
        Gate::define('admin', function ($user) {
            return $user->role === 'admin' || $user->role === 'superadmin';
        });
    
        Gate::define('superadmin', function ($user) {
            return $user->role === 'superadmin';
        });
    }
}
