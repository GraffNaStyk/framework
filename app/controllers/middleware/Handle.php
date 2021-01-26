<?php

namespace App\Controllers\Middleware;

use App\Facades\Http\Request;
use App\Facades\Http\Router;
use app\facades\log\Log;

class Handle
{
    public function before(Request $request, Router $router)
    {
        Log::info([
            'request' => $request->all(),
            'headers' => $request->headers(),
            'routeParams' => $router->getCurrentRoute()
        ]);
    }
}
