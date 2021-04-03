<?php

namespace App\Controllers\Middleware;

use App\Facades\Http\Request;
use App\Facades\Http\Router\Collection;
use App\Facades\Http\Router\Route;
use App\Facades\Http\Router\Router;
use App\Facades\Url\Url;
use App\Helpers\Session;
use App\Model\Right;

final class Auth
{
    private static array $methods = [
        1 => ['index', 'show'],
        2 => ['add', 'edit', 'store', 'update', 'upload'],
        3 => ['delete']
    ];

    public function before(Request $request, Router $router)
    {
        if (! Session::has('user')) {
            Route::redirect('/login');
        }

        if (! self::middleware($router->getCurrentRoute())) {
            Route::redirect(Url::base());
        }
    }
    
    public static function middleware(Collection $route): bool
    {
        if ($route->getRights() === 0) {
            return false;
        }
        
        if ($route->getRights() === 4) {
            return true;
        }

        if (class_exists(Right::class)) {
            $result = Right::select([strtolower($route->getController())])
                ->where('user_id', '=', \App\Core\Auth::id())
                ->first();

            if (empty($result) || $result[strtolower($route->getController())] < $route->getRights()) {
                return false;
            }
            
            $methods = self::$methods[1];
            
            if ($route->getRights() === 2) {
                $methods = [...self::$methods[1], ...self::$methods[2]];
            }
            
            if ($route->getRights() === 3) {
                $methods = [...self::$methods[1], ...self::$methods[2], ...self::$methods[3]];
            }
            
            if (! in_array($route->getAction(), $methods)) {
                return false;
            }
        }

        return true;
    }
}
