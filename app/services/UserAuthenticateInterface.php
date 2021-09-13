<?php

namespace App\Services;

use App\Facades\Http\Request;
use App\Model\User;

interface UserAuthenticateInterface
{
	public function __construct(User $user);

	public function authenticate(Request $request);
}
