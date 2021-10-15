<?php

namespace App\Services\Abstraction\User;

use App\Facades\Http\Request;

interface UserAuthenticateInterface
{
	public function authenticate(Request $request): bool;
}
