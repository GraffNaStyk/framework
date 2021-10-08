<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Facades\Http\Request;
use App\Facades\Http\View;
use App\Rules\LoginValidator;
use App\Services\Abstraction\User\UserAuthenticateInterface;

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

    public function check(Request $request, UserAuthenticateInterface $userAuthenticateService): string
    {
        if (! $this->validate($request->all(), LoginValidator::class)) {
             return $this->sendError('Formularz nie zostal wysłany');
        }

        if ($userAuthenticateService->authenticate($request)) {
	        return $this->sendSuccess('Zalogowano poprawnie', [
			        'to' => '/dash'
		        ]
	        );
        }

        return $this->sendError('Niepoprwane dane logowania');
    }
}
