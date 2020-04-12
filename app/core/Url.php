<?php namespace App\Core;

abstract class Url
{
    public static function get()
    {
        if (Router::isAdmin())
            return app['url'] . app['cms'] . '/';

        return app['url'];
    }

    public static function base()
    {
        return app['url'];
    }
}
