<?php namespace App\Controllers\Admin;

use App\Core\Router;
use App\Helpers\Session;

class LogoutController
{
    public function index()
    {
        Session::destroy();
        Router::redirect('Login');
    }
}
