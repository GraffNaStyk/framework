<?php

namespace App\Services\User;

use App\Controllers\UserState;
use App\Facades\Faker\Password;
use App\Facades\Http\Request;
use App\Model\User;
use App\Services\Abstraction\User\UserAuthenticateServiceInterface;

class UserAuthenticateService implements UserAuthenticateServiceInterface
{
	private User $user;
	
	public function __construct(User $user)
	{
		$this->user = $user;
	}
	
	public function authenticate(Request $request): bool
	{
		$user = $this->user->select()
			->where('name', '=', $request->get('name'))
			->exist();
		
		if ($user && Password::verify($request->get('password'), $user->password)) {
			UserState::login($user);
			return true;
		}
		
		return false;
	}
}