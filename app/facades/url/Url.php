<?php
namespace App\Facades\Url;

use App\Facades\Http\Router;

class Url
{

    public static function get()
    {
        $url = Router::getAlias() ? Router::getAlias() . '/' : Router::getAlias();
        return app['url'].$url;
    }

    public static function base()
    {
        return app['url'];
    }

    public static function segment($string, $offset, $delimiter = '/'): ?string
    {
        $string = explode($delimiter, $string);

        if($offset === 'end' || $offset === 'last')
            return end($string);

        if(isset($string[$offset]))
            return $string[$offset];

        return false;
    }

    public static function link(string $link): string
    {
        $link = strtolower(trim(preg_replace('~[^\\pL\d]+~u', '-', $link)));
        $link = iconv('utf-8', 'us-ascii//TRANSLIT', $link);
        $link = preg_replace('~[^-\w]+~', '', $link);
        return substr($link, 0, -1);
    }
    
    public static function isLocalhost(): bool
    {
        return in_array(getenv('REMOTE_ADDR'), ['127.0.0.1', '::1']) ? true : false;
    }
}
