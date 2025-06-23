<?php

use App\Http\Middleware\LogApiRequests;
use App\Http\Middleware\CheckBearerToken;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // api.php dosyasını tanıması için.
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(append: [
            LogApiRequests::class,
            CheckBearerToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Hata yönetimi için burası. Şimdilik boş kalabilir.
    })->create();