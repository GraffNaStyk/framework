<?php

namespace App\Controllers\Middleware;

use App\Facades\Http\Request;
use App\Facades\Http\Router\Router;
use App\Facades\Log\Log;

class Handle
{
    public function before(Request $request, Router $router)
    {
    	if ($request->isPost()) {
		    Log::custom('request', [
			    'routeParams' => $router->routeParams(),
		    ]);
	    }
    }
}
