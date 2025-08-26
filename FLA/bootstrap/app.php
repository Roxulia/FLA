<?php

use App\Http\Middleware\AdminAuthenticate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Exceptions\Handler;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        api : __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
       $middleware->alias(['check_role' => CheckRole::class]);
       $middleware->alias([
            'auth.admin' => AdminAuthenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (TokenExpiredException $e, $request) {
            return response()->json(['error' => 'Token has expired'], 401);
        });

        $exceptions->render(function (TokenInvalidException $e, $request) {
            return response()->json(['error' => 'Invalid token'], 401);
        });

        $exceptions->render(function (JWTException $e, $request) {
            return response()->json(['error' => 'Token not provided'], 401);
        });
    })->create();
