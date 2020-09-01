<?php namespace App\Core;

use App\Helpers\Session;
use App\Facades\Http\Router;

class Auth
{
    public static function guard(): void
    {
        if(!Session::has('user'))
            Router::redirect('login');
    }
    
    public static function isLocalhost(): bool
    {
        return in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) ? true : false;
    }
}
