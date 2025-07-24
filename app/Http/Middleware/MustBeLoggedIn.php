<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MustBeLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) return $next($request);

        // If request expects JSON (e.g. axios, fetch), return JSON error response
        if ($request->expectsJson()) {
            return response()->json([
                'validated' => false,
                'message' => 'You must be logged in.'
            ], 401);
        }

        return redirect('/')->with('failure', 'You must be logged in.');
    }
}
