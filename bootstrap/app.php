<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\NotFilamentUser;
use App\Http\Middleware\SendWelcomeEmailAfterVerification;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: [
            __DIR__ . '/../routes/api.php',
        ],
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append([
            \App\Http\Middleware\SendWelcomeEmailAfterVerification::class,
        ]);

        $middleware->alias([
            'role' => CheckRole::class,
            'not.filament' => NotFilamentUser::class,
            // 'sendWelcomeEmail' => SendWelcomeEmailAfterVerification::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
