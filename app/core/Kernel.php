<?php

namespace App\Core;

use App\Controllers\Middleware\IsLogged;

abstract class Kernel
{
    private static array $middlewares = [
        'auth' => \App\Controllers\Middleware\Auth::class,
        'isLogged' => IsLogged::class,
    ];
    
    private static array $everyMiddleware = [
        \App\Controllers\Middleware\Handle::class
    ];
    
    final public static function getMiddleware(string $middleware): ?string
    {
        return self::$middlewares[$middleware] ?: null;
    }
    
    final public static function getEveryMiddleware(): array
    {
        return self::$everyMiddleware;
    }
}
