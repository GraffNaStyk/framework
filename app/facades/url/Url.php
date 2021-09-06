<?php

namespace App\Facades\Url;

use App\Facades\Config\Config;
use App\Facades\Http\Router\Router;

class Url
{
    public static function get(): string
    {
        if (Router::getAlias() === 'http') {
            $url = Url::base().$url;
        } else {
            $url = Url::base().'/'.Router::getAlias().$url;
        }

        return $url;
    }

    public static function base(): string
    {
        if (Config::get('app.url') === '/') {
            return '';
        }

        return Config::get('app.url');
    }

    public static function segment($string, $offset, $delimiter = '/'): ?string
    {
        $string = explode($delimiter, $string);

        if ($offset === 'end' || $offset === 'last') {
            return end($string);
        }

        if (isset($string[$offset])) {
            return $string[$offset];
        }

        return false;
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
        if (empty(getenv('HTTP_HOST'))) {
            return Config::get('app.host_url').self::base();
        } else {
            return Router::checkProtocol().'://'.getenv('HTTP_HOST').self::base();
        }
    }
}
