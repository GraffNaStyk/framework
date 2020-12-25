<?php
namespace App\Db\Eloquent;

use App\Facades\Http\Router;

abstract class Handle
{
    public static function throwException($e, $error)
    {
        if (app['dev']) {
            print_r("<b>SQL Error</b>: {$e->getMessage()} <br>");
            pd("<b> Query </b>: {$error}");
        } else {
            $date = date('Y-m-d H:i:s');
            file_put_contents(storage_path('private/logs/sql_'.date('d-m-Y').'.log'),
            "[Date {$date}] {$e->getMessage()}" . PHP_EOL .
                  "Query: {$error} " . PHP_EOL .
                  "Trace ". Router::getClass()."->". Router::getAction() . '()' .PHP_EOL .
                  "---------------------------------------------" . PHP_EOL
            ,FILE_APPEND);
        }
    }
}
