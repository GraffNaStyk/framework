<?php
namespace App\Core;

use App\Helpers\Session;
use App\Facades\Http\Router;
use App\Model\Right;

class Auth
{
    public static function guard(): void
    {
        if (!Session::has('user')) {
            Router::redirect('login');
        }
    }
    
    public static function middleware(string $class, int $rights)
    {
        if ($rights === 4) {
            return true;
        }
        
        if (class_exists(Right::class)) {
            $result = Right::select([strtolower($class)])
                ->where(['user_id', '=', Session::get('user.id')])
                ->first()
                ->get();
            
            if (empty($result) || $result[strtolower($class)] < $rights) {
                return false;
            }
        }
        
        return true;
    }
}
