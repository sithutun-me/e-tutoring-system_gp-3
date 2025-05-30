<?php

use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RoleMiddleware;
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
        $middleware->append(\App\Http\Middleware\TrackBrowserUsage::class);
        $middleware->append(\App\Http\Middleware\TrackPageViews::class);
        $middleware->alias([
            'protect_auth' => App\Http\Middleware\ProtectAuth::class,
            'role' => App\Http\Middleware\RoleMiddleware::class,
            'guest' => App\Http\Middleware\RedirectIfAuthenticated::class,
            

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
