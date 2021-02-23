<?php

namespace App\Controllers\Admin;

use App\Facades\Http\Router\Route;
use App\Helpers\Session;

class LogoutController
{
    public function index()
    {
        Session::destroy();
        Route::redirect('/');
    }
}
