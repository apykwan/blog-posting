<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
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
        Gate::define('visitAdminPages', function($user) {
            return $user->isAdmin === 1;
        });

        Gate::define('deletePost', function ($user, $post) {
            return $user->id === $post->user_id || $user->isAdmin;
        });

        Paginator::useBootstrapFive();
    }
}
