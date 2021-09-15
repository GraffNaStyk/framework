<?php

namespace App\Facades\Url;

use App\Facades\Config\Config;
use App\Facades\Http\Router\Router;

class Url
{
    public static function get(): string
    {
    	return Router::getAlias() === 'http'
		    ? Url::base().$url
		    : Url::base().'/'.Router::getAlias().$url;
    }

    public static function base(): string
    {
        return Config::get('app.url') === '/' ? '' : Config::get('app.url');
    }

    public static function segment($string, $offset, $delimiter = '/'): ?string
    {
        $string = explode($delimiter, $string);

        if ($offset === 'end' || $offset === 'last') {
            return end($string);
        }
        
        return isset($string[$offset])
	        ? $string[$offset]
			: null;
    }

    public static function link(string $link): string
    {
        $link = strtolower(trim(preg_replace('~[^\\pL\d]+~u', '-', $link)));
        $link = iconv('utf-8', 'us-ascii//TRANSLIT', $link);
        $link = preg_replace('~[^-\w]+~', '', $link);
        return substr($link, 0, - 1);
    }

    public static function isLocalhost(): bool
    {
        return in_array(getenv('REMOTE_ADDR'), ['127.0.0.1', '::1']);
    }

    public static function full(): string
    {
	    return empty(getenv('HTTP_HOST'))
		    ? Config::get('app.host_url') . self::base()
		    : Router::checkProtocol() . '://' . getenv('HTTP_HOST') . self::base();
    }
}
