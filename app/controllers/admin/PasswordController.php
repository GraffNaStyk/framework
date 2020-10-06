<?php

namespace App\Controllers\Admin;

use App\Facades\Faker\Hash;
use App\Facades\Http\Request;
use App\Facades\Http\View;
use App\Facades\Validator\Validator;
use App\Helpers\Session;
use App\Model\User;

class PasswordController extends DashController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        return View::render();
    }
    
    public function store(Request $request)
    {
        if(!Validator::make($request->all(), [
            'password' => 'required|min:6'
        ])) return $this->sendError();
        
        User::where(['id' ,'=', Session::get('user.id')])
            ->update(['password' => Hash::crypt($request->get('password'))]);
    
        return $this->sendSuccess('Hasło zostało zresetowane', '', 202);
    }
}
