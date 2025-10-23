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
        // Global & route middleware aliases
        $middleware->alias([
            'admin.only' => \App\Http\Middleware\AdminOnly::class,
        ]);

        // Force a single local origin: redirect 127.0.0.1 to localhost
        $middleware->append(\App\Http\Middleware\ForceLocalhost::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
