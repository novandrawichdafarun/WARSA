<?php

use App\Http\Middleware\EnsureIsKasir;
use App\Http\Middleware\EnsureIsOwner;
use App\Http\Middleware\EnsureIsOwnerOrSuperAdmin;
use App\Http\Middleware\EnsureIsSuperAdmin;
use App\Http\Middleware\EnsureWarungSetup;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__ . '/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->preventRequestForgery(except: [
            //
        ]);

        $middleware->alias([
            'owner' => EnsureIsOwner::class,
            'kasir' => EnsureIsKasir::class,
            'warung.setup' => EnsureWarungSetup::class,
            'super_admin' => EnsureIsSuperAdmin::class,
            'owner_or_superadmin' => EnsureIsOwnerOrSuperAdmin::class,
        ]);

        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Exception $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
            return null;
        });
    })->create();
