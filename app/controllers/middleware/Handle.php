<?php

namespace App\Controllers\Middleware;

use App\Facades\Http\Request;
use App\Facades\Http\Router\Router;
use App\Facades\Log\Log;

class Handle
{
	public function before(Request $request, Router $router)
	{
		Log::custom('request', [
			'request' => $request->all(),
			'routeParams' => $router->routeParams(),
		]);
	}
}
