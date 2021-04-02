<?php

namespace App\Db\Eloquent;

use App\Facades\Http\Router\Router;
use App\Facades\Log\Log;

abstract class Handle
{
    public static function throwException($e, $error)
    {
        if (app('dev')) {
            print_r("<b>SQL Error</b>: {$e->getMessage()} <br>");
            pd("<b> Query </b>: {$error}", true);
        }
        
        $router = Router::getInstance();
        $router->request->remove('password');
        
        Log::sql([
            'error' => $e->getMessage(),
            'query' => $error,
            'request' => $router->request->all(),
            'routeParams' => $router->routeParams()
        ]);
    }
}
