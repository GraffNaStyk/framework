<?php

namespace App\Facades\Db;

use App\Facades\Config\Config;
use App\Facades\Log\Log;

abstract class Handle
{
    public static function throwException($e, $error)
    {
        if (Config::get('app.dev')) {
            print_r("<b>SQL Error</b>: {$e->getMessage()} <br>");
            pd("<b> Query </b>: {$error}", true);
        }

        Log::sql([
            'error' => $e->getMessage(),
            'query' => $error,
        ]);
    }
}
