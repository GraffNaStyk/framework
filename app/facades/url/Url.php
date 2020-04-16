<?php namespace App\Facades\Url;

use App\Core\Router;

class Url
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

    public static function segment($string, $offset, $delimiter = '/')
    {
        $string = explode($delimiter, $string);

        if($offset == 'end' || $offset == 'last')
            return end($string);

        if(isset($string[$offset]))
            return $string[$offset];

        return false;
    }

    public static function link(string $link):string
    {
        $link = strtolower(trim(preg_replace('~[^\\pL\d]+~u', '-', $link)));

        $link = iconv('utf-8', 'us-ascii//TRANSLIT', $link);

        $link = preg_replace('~[^-\w]+~', '', $link);

        return substr($link, 0, -1);
    }

}
