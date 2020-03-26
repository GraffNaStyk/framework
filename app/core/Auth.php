<?php namespace App\Core;

use App\Helpers\Session;

class Auth
{
    public static function guard()
    {
        if(!Session::has('user'))
            Router::redirect('Login');
    }
}
