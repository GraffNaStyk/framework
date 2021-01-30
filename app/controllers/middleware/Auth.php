<?php

namespace App\Controllers\Middleware;

use App\Facades\Http\Request;
use App\Facades\Http\Route;
use App\Facades\Http\Router;
use App\Facades\Url\Url;
use App\Helpers\Session;
use App\Model\Right;

final class Auth
{
    private static array $methods = [
        1 => ['index', 'show'],
        2 => ['add', 'edit', 'store', 'update'],
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
    
    public static function middleware(array $route): bool
    {
        if ($route['rights'] === 4) {
            return true;
        }
        
        if ($route['rights'] === 0) {
            return false;
        }
  
        if (class_exists(Right::class)) {
            $result = Right::select([strtolower($route['controller'])])
                ->where('user_id', '=', Session::get('user.id'))
                ->first();

            if (empty($result) || $result[strtolower($route['controller'])] < $route['rights']) {
                return false;
            }
            
            $methods = [];
            
            if ($route['rights'] === 1) {
                $methods = self::$methods[1];
            }
            
            if ($route['rights'] === 2) {
                $methods = [...self::$methods[1], ...self::$methods[2]];
            }
            
            if ($route['rights'] === 3) {
                $methods = [...self::$methods[1], ...self::$methods[2], ...self::$methods[3]];
            }
            
            if (! in_array($route['action'], $methods)) {
                return false;
            }
        }

        return true;
    }
}
