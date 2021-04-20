<?php

namespace App\Controllers\Admin;

use App\Facades\Http\Router\Route;
use App\Facades\Http\Session;

class LogoutController
{
    public function index()
    {
        Session::destroy();
        Route::redirect('/');
    }
}
