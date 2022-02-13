<?php

namespace App\Controllers\Middleware;

use App\Facades\Http\Request;
use App\Facades\Http\Router\Router;
use App\Facades\Log\Log;

class Handle
{
    public function after(Request $request, Router $router): void
    {
    	if ($request->isPost() && ! empty($request->all())) {
		    Log::custom('request', [
			    'routeParams' => $router->routeParams(),
		    ]);
	    }
    }
}
