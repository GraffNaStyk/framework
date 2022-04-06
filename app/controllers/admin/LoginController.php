<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Facades\Http\Request;
use App\Facades\Http\Response;
use App\Facades\Http\View;
use App\Models\Client;
use App\Rules\LoginValidator;
use App\Services\Abstraction\User\UserAuthenticateInterface;

class LoginController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Client $client): Response
    {
        View::layout('login');
        return $this->render(['title' => 'Panel Administracyjny - logowanie']);
    }

    public function check(
    	Request $request,
	    UserAuthenticateInterface $userAuthenticateService,
	    LoginValidator $validator
    ): Response
    {
        if (! $this->validate($request->all(), $validator)) {
             return $this->sendError('Formularz nie zostal wysłany');
        }

        if ($userAuthenticateService->authenticate()) {
	        return $this->sendSuccess('Zalogowano poprawnie', [
			        'to' => $this->getRoute('Dash@index')
		        ]
	        );
        }

        return $this->sendError('Niepoprwane dane logowania');
    }
}
