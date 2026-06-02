<?php

use App\Http\Middleware\EnsureIsKasir;
use App\Http\Middleware\EnsureIsOwner;
use App\Http\Middleware\EnsureWarungSetup;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->preventRequestForgery(except: [
            'webhook/midtrans',
        ]);

        $middleware->alias([
            'owner' => EnsureIsOwner::class,
            'kasir' => EnsureIsKasir::class,
            'warung.setup' => EnsureWarungSetup::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Exception $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }
        });

        $exceptions->render(function (\Midtrans\Exception\ApiException $e, Request $request) {
            Log::error('Midtrans API error: ' . $e->getMessage());
            return back()->with('error', 'Layanan pembayaran sedang bermasalah. Coba beberapa saat lagi.');
        });
    })->create();
