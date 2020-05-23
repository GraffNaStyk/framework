<?php namespace App\Controllers\Admin;

use App\Facades\Http\Router;
use App\Helpers\Session;

class LogoutController
{
    public function index()
    {
        Session::destroy();
        Router::redirect('');
    }
}
