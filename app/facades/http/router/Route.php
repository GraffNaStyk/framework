<?php

namespace App\Facades\Http\Router;

use App\Facades\Cache\Cache;
use App\Facades\Config\Config;
use App\Facades\Csrf\Csrf;
use App\Facades\Url\Url;

abstract class Route
{
    protected static array $routes;
    protected static string $namespace;
    protected static ?string $middleware = null;
    protected static ?string $alias = null;
    protected static array $urls = [];

    public static function namespace(string $namespace, callable $function): void
    {
        static::$namespace = $namespace;
        $function();
        static::$middleware = null;
    }

    public static function middleware(string $middleware, callable $function): void
    {
        static::$middleware = ucfirst($middleware);
        $function();
        static::$middleware = null;
    }

    public static function get(string $url, string $controller, int $rights = 4): Collection
    {
        return self::match($url, $controller, 'get', $rights);
    }

    public static function post(string $url, string $controller, int $rights = 4): Collection
    {
        return self::match($url, $controller, 'post', $rights);
    }

    public static function put(string $url, string $controller, int $rights = 4): Collection
    {
        return self::match($url, $controller, 'put', $rights);
    }

    public static function group(
        array $urls,
        string $controller,
        string $method = 'post',
        int $rights = 4,
        array $middlewares = []
    ): void
    {
        foreach ($urls as $url) {
            $collection = self::match($url, $controller, $method, $rights);
            $collection->middleware($middlewares);
        }
    }

    public static function delete(string $url, string $controller, int $rights = 4): Collection
    {
        return self::match($url, $controller, 'delete', $rights);
    }

    public static function alias(string $alias, callable $function): void
    {
        self::$alias = $alias;
        $function();
        self::$alias = null;
    }

    public static function crud(string $url, string $controller, int $rights = 4): void
    {
        self::get($url, $controller.'@index', $rights);
        self::get($url.'/add', $controller.'@add', $rights);
        self::get($url.'/edit/{id}', $controller.'@edit', $rights);
        self::get($url.'/show/{id}', $controller.'@show', $rights);
        self::post($url.'/store', $controller.'@store', $rights);
        self::post($url.'/update', $controller.'@update', $rights);
        self::post($url.'/delete', $controller.'@delete', $rights);
    }

    private static function match(string $as, string $route, string $method, int $rights): Collection
    {
        $routes     = explode('@', $route);
		$namespace  = self::$namespace;
		$middleware = self::$middleware;
	    $alias      = self::$alias;
	   
        $collection = Cache::remember(19000, '/routes', 'route_'.$route,
	        function () use ($routes, $method, $rights, $middleware, $alias, $namespace)
	        {
		         return new Collection(
			         ucfirst($routes[0]),
			         $routes[1] ?? 'index',
			         $namespace,
			         $method,
			         $rights,
			         $middleware,
			         $alias
		        );
        });

        if (self::$alias === null) {
            $url = self::$alias.$as ?? $routes[0].'/'.$routes[1];
        } else {
            $url = self::$alias.rtrim($as, '/') ?? $routes[0].'/'.$routes[1];
        }

        self::$routes[$url] = $collection;

        if ($method !== 'get' && ! defined('API')) {
            Csrf::make($route);
        }

        return $collection;
    }

    public static function when(string $when, string $then): void
    {
        if (Config::get('app.url') !== '/') {
            $route = rtrim(str_replace(Config::get('app.url'), '', Router::url()), '/');
        } else {
            $route = rtrim(Router::url(), '/');
        }

        if ($route === rtrim($when, '/')) {
            static::redirect($then);
        }
    }

    public static function redirect(string $path, int $code = 302, bool $direct = false): void
    {
        session_write_close();
        session_regenerate_id();

        if ($direct) {
            header(
                'location: '.self::checkProtocol().'://'.$_SERVER['HTTP_HOST'].Url::base().$path,
                true,
                $code
            );
        } else {
            header(
                'location: '.self::checkProtocol().'://'.$_SERVER['HTTP_HOST'].Url::get().$path,
                true,
                $code
            );
        }

        exit;
    }

    public static function goTo(string $url): void
    {
        header('location: '.$url, true, 301);
        exit;
    }

    public static function checkProtocol(): string
    {
        return isset($_SERVER['HTTPS']) || (int) $_SERVER['SERVER_PORT'] === 443 ? 'https' : 'http';
    }

    public static function urls(): array
    {
        return self::$urls;
    }
}
