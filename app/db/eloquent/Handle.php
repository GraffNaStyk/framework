<?php
namespace App\Db\Eloquent;

use App\Facades\Http\Router;
use app\facades\log\Log;

abstract class Handle
{
    public static function throwException($e, $error)
    {
        if (app('dev')) {
            print_r("<b>SQL Error</b>: {$e->getMessage()} <br>");
            pd("<b> Query </b>: {$error}", true);
        }
    
        Log::sql([
            'error' => $e->getMessage(),
            'Query' => $error,
        ]);
    }
}
