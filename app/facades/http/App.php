<?php

namespace App\Facades\Http;

use App\Facades\Header\Header;
use App\Facades\Http\Router\Router;

final class App
{
	public Router $router;
	const PER_PAGE = 25;
	
	public function __construct(Router $router)
	{
		$this->router = $router;
	}
	
	public function run(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}

		Header::set();
	}
}
