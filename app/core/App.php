<?php

namespace App\Core;

use App\Facades\Db\Db;
use App\Facades\Env\Env;
use App\Facades\Http\Router\Router;
use App\Helpers\Loader;

final class App
{
	public Router $router;
	
	public function __construct(Router $router)
	{
		$this->router = $router;
	}
	
	const PER_PAGE = 25;
	
	public function run(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
		
		Env::set();
		Db::init();
		Loader::set();
	}
}
