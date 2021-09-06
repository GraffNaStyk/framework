<?php

namespace App\Services;

use App\Facades\Http\Request;
use App\Model\User;

interface UserAuthenticateInterface
{
	function __construct(User $user);
	function authenticate(Request $request);
}
