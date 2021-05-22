<?php

namespace App\Facades\Http\Router;

use App\Facades\Csrf\Csrf;
use App\Facades\Url\Url;

abstract class Route
{
    protected static array $routes;
    protected static string $namespace;
    protected static ?string $middleware = null;
    protected static ?string $alias = null;
    
    public static function namespace(string $namespace, callable $function)
    {
	    static::$namespace = $namespace;
	    $function();
	    static::$middleware = null;
    }
    
    public static function middleware(string $middleware, callable $function)
    {
        static::$middleware = $middleware;
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
    
    public static function put(string $url, string $controller, int $rights=4): Collection
    {
	    return self::match($url, $controller, 'put', $rights);
    }
    
    public static function group(array $urls, string $controller, string $method='post', int $rights=4): Collection
    {
    	$lastKey = array_key_last($urls);

    	foreach ($urls as $key => $url) {
    		if ($lastKey === $key) {
    			return self::match($url, $controller, $method, $rights);
		    }

		    self::match($url, $controller, $method, $rights);
	    }
    }

    public static function delete(string $url, string $controller, int $rights=4): Collection
    {
	    return self::match($url, $controller, 'delete', $rights);
    }

    public static function alias(string $alias, callable $function): void
    {
        self::$alias = $alias;
        $function();
        self::$alias = null;
    }
	
	public static function crud(string $url, string $controller, int $rights=4)
	{
		self::get($url, $controller.'@index', $rights);
		self::get($url.'/add', $controller.'@add', $rights);
		self::get($url.'/edit/{id}', $controller.'@edit', $rights);
		self::get($url.'/show/{id}', $controller.'@show', $rights);
		self::post($url.'/store', $controller.'@store', $rights);
		self::post($url.'/update', $controller.'@update', $rights);
		self::get($url.'/delete/{id}', $controller.'@delete', $rights);
	}
    
    private static function match(string $as, string $route, string $method, int $rights): Collection
    {
        $routes = explode('@', $route);

        $collection = new Collection(
	        ucfirst($routes[0]),
	        lcfirst($routes[1]) ?? 'index',
	        self::$namespace,
	        $method,
	        $rights,
	        self::$middleware
        );

	    self::$routes[self::$alias . $as ?? $routes[0].'/'.$routes[1]] = $collection;

	    if ($method !== 'get') {
		    Csrf::make($route);
	    }
	    
	    return $collection;
    }
    
    public static function when(string $when, string $then)
    {
        if (app('url') !== '/') {
            $route = rtrim(str_replace(app('url'), '', Router::url()), '/');
        } else {
            $route = rtrim(Router::url(), '/');
        }
        
        if ($route === rtrim($when, '/')) {
            static::redirect($then);
        }
    }
    
    public static function redirect(string $path, int $code=302, bool $direct=false): void
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
}
