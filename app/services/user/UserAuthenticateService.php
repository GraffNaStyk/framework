<?php

namespace App\Services\User;

use App\Controllers\UserState;
use App\Facades\Helpers\Password;
use App\Facades\Http\Request;
use App\Models\User;
use App\Services\Abstraction\User\UserAuthenticateInterface;

class UserAuthenticateService implements UserAuthenticateInterface
{
	public function __construct(private User $user, private Request $request){}
	
	public function authenticate(): bool
	{
		$user = $this->user->select()
			->where('name', '=', $this->request->get('name'))
			->exist();

		if ($user && Password::verify($this->request->get('password'), $user->password)) {
			UserState::login($user);
			return true;
		}
		
		return false;
	}
}
