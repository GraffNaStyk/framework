<?php namespace App\Db\Eloquent;

abstract class Handle
{
    public static function throwException($e)
    {
        if (app['dev'])
            pd(trigger_error("<br> <b>SQL Error</b>: {$e->getMessage()}"));
    }
}
