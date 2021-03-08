<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Facades\Faker\Hash;
use App\Facades\Faker\Password;
use App\Helpers\Session;
use App\Model\Client;
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
    	pd(Client::selectGroup(['race', 'name', 'health'])->get());
        View::layout('login');
        return View::render(['title' => 'Panel Administracyjny - logowanie']);
    }

    public function check(Request $request)
    {
        if (! $this->validate($request->all(), 'login')) {
            $this->sendError('Formularz nie zostal wysłany');
        }
	
	    $user = User::select(['name', 'id', 'password'])
		    ->where('name', '=', $request->get('name'))
		    ->exist();

	    if ($user && Password::verify($request->get('password'), $user->password)) {
            unset($user->password);
            Session::set('user', $user);
            $this->sendSuccess('Zalogowano poprawnie', '/dash');
        }
    
        $this->sendError('Niepoprwane dane logowania');
    }
}
