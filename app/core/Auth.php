<?php

namespace App\Core;

use App\Helpers\Session;
use App\Model\User;

class Auth
{
	public static function id(): int
	{
		return (int) Session::get('user.id');
	}
	
	public static function user(): object
	{
		return (object) Session::get('user');
	}

	public static function login(object $user): void
	{
		unset($user->password);
		Session::set('user', $user);
	}
	
	public static function refresh(): void
	{
		Session::set('user', 
			User::select()->where('id', '=', self::id())->exist()
		);
	}
}
