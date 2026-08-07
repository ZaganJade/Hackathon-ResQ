<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Vercel (and similar serverless platforms) terminate TLS at their edge
        // and forward requests to the function over plain HTTP, so Laravel must
        // trust that edge to read X-Forwarded-Proto; otherwise url()/asset()
        // generate http:// links even though the site is served over https://.
        // The function is only ever reachable through that edge, so trusting
        // all proxies is safe here.
        $middleware->trustProxies(at: '*');

        // Global middleware - applied to all routes. `use()` replaces Laravel's
        // default global middleware list entirely (rather than appending to
        // it), so TrustProxies must be listed explicitly here or the
        // trustProxies() config above never actually runs against requests.
        $middleware->use([
            \Illuminate\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        // Route middleware aliases
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
