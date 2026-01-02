<?php
// app/Http/Middleware/StudentMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class StudentMiddleware
{
    /**
     * Handle an incoming request.
     * Only allow users with 'student' role to proceed.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to continue.');
        }

        if (!Auth::user()) {
            abort(403, 'Unauthorized access. Student privileges required.');
        }

        return $next($request);
    }
}

// app/Http/Kernel.php (or bootstrap/app.php for Laravel 11+)
// Register middleware aliases

// For Laravel 10 and below, add to $middlewareAliases in app/Http/Kernel.php:
/*
protected $middlewareAliases = [
    // ... other middleware
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
    'student' => \App\Http\Middleware\StudentMiddleware::class,
];
*/

// For Laravel 11+, register in bootstrap/app.php:
/*
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\StudentMiddleware;

->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'admin' => AdminMiddleware::class,
        'student' => StudentMiddleware::class,
    ]);
})
*/