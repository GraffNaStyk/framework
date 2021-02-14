<?php

namespace App\Facades\Csrf;

use App\Facades\Faker\Faker;
use App\Facades\Http\Request;
use App\Facades\Http\Router;
use App\Helpers\Session;

class Csrf
{
	
	public function isValid(string $csrf): bool
	{
		return (string) Session::get('@csrf.'.Router::csrfPath()) === $csrf;
	}
	
	public function valid(Request $request): bool
	{
		if (! $request->has('_csrf') && app('csrf')) {
			return false;
		}
		
		$result = $this->isValid($request->get('_csrf'));
		$request->remove('_csrf');
		Session::remove('@csrf.'.Router::csrfPath());
		self::make(Router::csrfPath());
		return $result;
	}
	
	public static function make(string $uri)
	{
		if (! Session::has('@csrf.'.$uri) && app('csrf')) {
			Session::set('@csrf.'.$uri, Faker::hash(60));
		}
	}
}
