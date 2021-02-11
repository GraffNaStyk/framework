<?php

namespace App\Core;

use App\Controllers\Middleware\IsLogged;
use \App\Controllers\Middleware\Auth;
use \App\Controllers\Middleware\Handle;

abstract class Kernel
{
    private static array $middlewares = [
        'auth' => Auth::class,
        'isLogged' => IsLogged::class,
    ];
    
    private static array $everyMiddleware = [
        Handle::class
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
