<?php

namespace App\Controllers\Middleware;

use App\Facades\Http\Request;
use app\facades\log\Log;

class Handle
{
    public function handle(Request $request, array $routeParams)
    {
        Log::info([
            'request' => $request->all(),
            'headers' => $request->headers(),
            'routeParams' => $routeParams
        ]);
    }
}
