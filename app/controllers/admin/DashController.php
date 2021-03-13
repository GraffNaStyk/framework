<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Facades\Http\Request;
use App\Facades\Http\View;
use App\Helpers\Storage;
use App\Model\File;
use App\Model\User;

class DashController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return View::render([
                'users' => User::select(['name as value', 'password as text'])->get(),
                'img' => File::select([File::selectPath()])->where('id', '=', 5)->first(),
	            'options' => ['test' => 'raz', 'dwa' => 'trzy', 'twoj' => 'stary']
            ]);
    }
    
    public function users($name): string
    {
        return $this->response(
            User::select(['name as text', 'password as value'])->get()
        );
    }
    
    public function upload(Request $request)
    {
        Storage::disk('public')->upload($request->file('file'));
    }
}
