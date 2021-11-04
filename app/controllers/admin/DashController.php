<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Facades\Http\Response;
use App\Models\File;
use App\Models\User;

class DashController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): Response
    {
        return $this->render([
	        'users' => User::select(['name as value', 'password as text'])->get(),
	        'img' => File::select([File::selectPath()])->where('id', '=', 5)->first(),
	        'options' => ['test' => 'raz', 'dwa' => 'trzy', 'twoj' => 'stary'],
        ]);
    }
}
