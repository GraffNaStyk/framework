<?php

namespace App\Core;

abstract class Kernel
{
    private static array $middlewares = [
        'auth'     => \App\Controllers\Middleware\Auth::class,
        'isLogged' => \App\Controllers\Middleware\IsLogged::class,
    ];
    
    private static array $everyMiddleware = [
	    \App\Controllers\Middleware\Handle::class
    ];
    
    final public static function getMiddlewares(array $middlewares): array
    {
    	$return = [];

    	foreach ($middlewares as $middleware) {
		    if (isset(self::$middlewares[$middleware])) {
		    	$return[] = self::$middlewares[$middleware];
		    }
	    }

        return $return;
    }
    
    final public static function getEveryMiddleware(): array
    {
        return self::$everyMiddleware;
    }
}
