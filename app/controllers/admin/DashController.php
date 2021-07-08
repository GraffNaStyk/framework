<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Facades\Http\Request;
use App\Facades\Http\View;
use App\Facades\Storage\Storage;
use App\Model\File;
use App\Model\User;

class DashController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): string
    {
        return View::render([
            'users' => User::select(['name as value', 'password as text'])->get(),
            'img' => File::select([File::selectPath()])->where('id', '=', 5)->first(),
            'options' => ['test' => 'raz', 'dwa' => 'trzy', 'twoj' => 'stary'],
        ]);
    }

    public function upload(Request $request)
    {
        Storage::disk()->upload($request->file('file'));
    }
}
