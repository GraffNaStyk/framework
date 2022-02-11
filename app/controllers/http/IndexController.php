<?php

namespace App\Controllers\Http;

use App\Controllers\Controller;
use App\Facades\Http\Cookie;
use App\Facades\Http\Request;
use App\Facades\Http\Response;
use App\Models\User;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
	    Cookie::remove('test');
    }

    public function index(User $user, Request $request): Response
    {
    	dd($request->cookie->all(), $request->server->all());
        return $this->render();
    }
}
