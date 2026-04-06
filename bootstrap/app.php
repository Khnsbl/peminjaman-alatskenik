<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias middleware role
        $middleware->alias([
            'admin'     => \App\Http\Middleware\AdminMiddleware::class,
            'petugas'   => \App\Http\Middleware\PetugasMiddleware::class,
            'role.user' => \App\Http\Middleware\UserMiddleware::class,
        ]);

        // Ganti RedirectIfAuthenticated bawaan Laravel
        $middleware->redirectUsersTo(function ($request) {
            $role = \Illuminate\Support\Facades\Auth::user()?->role;

            return match($role) {
                'admin'   => route('admin.dashboard'),
                'petugas' => route('petugas.dashboard'),
                default   => route('user.dashboard'),
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();