<?php

namespace App\Services\Abstraction\User;

interface UserAuthenticateInterface
{
	public function authenticate(): bool;
}
