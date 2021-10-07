<?php

namespace App\Services\Abstraction\User;

use App\Facades\Http\Request;
use App\Model\User;

interface UserAuthenticateServiceInterface
{
	public function __construct(User $user);
	
	public function authenticate(Request $request);
}
