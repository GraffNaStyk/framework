<?php

namespace App\Controllers\Admin;

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
        ])) return $this->response(['ok' => false, 'msg' => Validator::getErrors()], 400);
        
        User::where(['id' ,'=', Session::get('user.id')])
            ->update(['password' => password_hash($request->get('password'), PASSWORD_BCRYPT)]);
    
        return $this->response(['ok' => true, 'msg' => ['Hasło zostało zresetowane']]);
    }
}

