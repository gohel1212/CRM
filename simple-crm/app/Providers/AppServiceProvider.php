<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Pagination\Paginator;

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
        Paginator::useBootstrap();

        // Blade permission and role directives
        Blade::if('perm', function (string $permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        Blade::if('role', function (string $role) {
            return auth()->check() && auth()->user()->role === $role;
        });
    }
}
