<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        View::composer('components.layout', function ($view) {
            $user = Auth::user();
            $token = $user ? JWTAuth::fromUser($user) : null;
            $view->with('jwtToken', $token);
        });

        Gate::define('visitAdminPages', function($user) {
            return $user->isAdmin === 1;
        });

        Gate::define('deletePost', function ($user, $post) {
            return $user->id === $post->user_id || $user->isAdmin;
        });

        Paginator::useBootstrapFive();
    }
}
