<?php namespace App\Core;

use App\Helpers\Session;
use App\Facades\Http\Router;

class Auth
{
    public static function guard(): void
    {
        if (!Session::has('user')) {
            Router::redirect('login');
        }
    }
}
