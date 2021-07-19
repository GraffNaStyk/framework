<?php

namespace App\Core;

abstract class Kernel
{
    private static array $everyMiddleware = [
        \App\Controllers\Middleware\Handle::class
    ];

    final public static function getEveryMiddleware(): array
    {
        return self::$everyMiddleware;
    }
}
