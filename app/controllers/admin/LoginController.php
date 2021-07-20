<?php

namespace App\Controllers\Admin;

use App\Controllers\Auth;
use App\Controllers\Controller;
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

    public function index(): string
    {
        View::layout('login');
        return $this->render(['title' => 'Panel Administracyjny - logowanie']);
    }

    public function check(Request $request): string
    {
        if (! $this->validate($request->all(), LoginValidator::class)) {
             return $this->sendError('Formularz nie zostal wysłany');
        }

        $user = User::select()
            ->where('name', '=', $request->get('name'))
            ->exist();

        if ($user && Password::verify($request->get('password'), $user->password)) {
            Auth::login($user);

            return $this->sendSuccess('Zalogowano poprawnie', [
                    'to' => '/dash'
                ]
            );
        }

        return $this->sendError('Niepoprwane dane logowania');
    }
}
