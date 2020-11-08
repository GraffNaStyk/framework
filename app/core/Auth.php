<?php
namespace App\Core;

use App\Helpers\Session;
use App\Facades\Http\Router;
use App\Model\Right;

class Auth
{
    private static array $methods = [
        1 => ['index', 'show'],
        2 => ['add', 'edit', 'store', 'update'],
        3 => ['delete']
    ];
    
    public static function guard(): void
    {
        if (!Session::has('user')) {
            Router::redirect('login');
        }
    }
    
    public static function middleware(string $class, string $action, int $rights)
    {
        if ($rights === 4) {
            return true;
        }

        if ($rights === 0) {
            return false;
        }
        
        if (class_exists(Right::class)) {
            $result = Right::select([strtolower($class)])
                ->where(['user_id', '=', Session::get('user.id')])
                ->first()
                ->get();
            
            if (empty($result) || $result[strtolower($class)] < $rights) {
                return false;
            }
    
            $methods = [];

            if ($rights === 1) {
                $methods = self::$methods[1];
            }
            
            if ($rights === 2) {
                $methods = [...self::$methods[1], ...self::$methods[2]];
            }
    
            if ($rights === 3) {
                $methods = [...self::$methods[1], ...self::$methods[2], ...self::$methods[3]];
            }
            
            if (! in_array($action, $methods)) {
                return false;
            }
        }
        
        return true;
    }
}
