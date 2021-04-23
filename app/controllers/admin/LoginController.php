<?php

namespace App\Controllers\Admin;

use App\Controllers\Auth;
use App\Controllers\Controller;
use App\Facades\Faker\Hash;
use App\Facades\Faker\Password;
use App\Facades\Http\Request;
use App\Facades\Http\View;
use App\Model\User;
use App\Rules\LoginValidator;

class LoginController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
    {
        View::layout('login');
        return $this->render(['title' => 'Panel Administracyjny - logowanie']);
    }

    public function check(Request $request)
    {
        if (! $this->validate($request->all(), LoginValidator::class)) {
            $this->sendError('Formularz nie zostal wysłany');
        }
	
	    $user = User::select()
		    ->where('name', '=', $request->get('name'))
		    ->exist();

	    if ($user && Password::verify($request->get('password'), $user->password)) {
            Auth::login($user);
            $this->sendSuccess('Zalogowano poprawnie', '/dash');
        }
    
        return $this->sendError('Niepoprwane dane logowania');
    }
}
