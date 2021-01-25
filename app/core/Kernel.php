<?php

namespace App\Core;

abstract class Kernel
{
    private static array $middlewares = [
        'auth' => \App\Controllers\Middleware\Auth::class,
        'example' => \App\Controllers\Middleware\EExample::class
    ];
    
    private static array $everyMiddleware = [
        \App\Controllers\Middleware\Handle::class
    ];
    
    final public static function getMiddleware(string $middleware): ?string
    {
        return isset(self::$middlewares[$middleware]) ? self::$middlewares[$middleware] : null;
    }
    
    final public static function getEveryMiddleware(): array
    {
        return self::$everyMiddleware;
    }
}
