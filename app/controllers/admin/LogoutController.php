<?php

namespace App\Controllers\Admin;

use App\Facades\Http\AbstractController;
use App\Facades\Http\Response;
use App\Facades\Http\Session;

class LogoutController extends AbstractController
{
    public function index(): Response
    {
        Session::destroy();
        return $this->redirect('/');
    }
}
