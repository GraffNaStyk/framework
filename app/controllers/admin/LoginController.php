<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Facades\Faker\Hash;
use App\Helpers\Session;
use App\Model\User;
use App\Facades\Http\View;
use App\Facades\Http\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        View::layout('login');
        return View::render(['title' => 'Panel Administracyjny - logowanie']);
    }

    public function check(Request $request)
    {
        if (! $this->validate($request->all(), 'login')) {
            $this->sendError();
        }
        
        if ($user = User::select(['name', 'id', 'password'])
            ->where('name', '=', $request->get('name'))
            ->exist()
        ) {
            if (Hash::verify($request->get('password'), $user['password'])) {
                unset($user['password']);
                Session::set(['user' => $user]);
                $this->sendSuccess('Zalogowano poprawnie', '/dash');
            }
            $this->sendError('Niepoprwane dane logowania');
        } else {
            $this->sendError('Niepoprwane dane logowania');
        }
    
        $this->sendError('Niepoprwane dane logowania');
    }
}
