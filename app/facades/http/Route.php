<?php

namespace App\Facades\Http;

use App\Facades\Csrf\Csrf;
use App\Facades\Url\Url;
use App\Helpers\Session;

abstract class Route
{
    protected static array $routes;
    protected static string $namespace;
    protected static ?string $middleware = null;
    protected static ?string $alias = null;
    
    public static function namespace(string $namespace, callable $function, array $middleware = [])
    {
        static::$namespace = $namespace;
        
        if (! empty($middleware)) {
            static::$middleware = $middleware['middleware'];
        }
        
        $function();
        static::$middleware = null;
    }
    
    public static function middleware(string $middleware, callable $function)
    {
        static::$middleware = $middleware;
        $function();
        static::$middleware = null;
    }
    
    public static function get(string $url, string $controller, int $rights = 4, string $middleware=null): void
    {
        self::match($url, $controller, 'get', $rights, $middleware);
    }
    
    public static function post(string $url, string $controller, int $rights = 4, string $middleware=null): void
    {
        self::match($url, $controller, 'post', $rights, $middleware);
    }
    
    public static function put(string $url, string $controller, int $rights = 4, string $middleware=null): void
    {
        self::match($url, $controller, 'put', $rights, $middleware);
    }
    
    public static function delete(string $url, string $controller, int $rights = 4, string $middleware=null): void
    {
        self::match($url, $controller, 'delete', $rights, $middleware);
    }
    
    public static function prefix(string $alias, callable $function): void
    {
        self::$alias = $alias;
        $function();
        self::$alias = null;
    }
	
	public static function crud(string $url, string $controller, int $rights = 4, string $middleware = null)
	{
		self::get($url, $controller.'@index', $rights, $middleware);
		self::get($url.'/add', $controller.'@add', $rights, $middleware);
		self::get($url.'/edit/{id}', $controller.'@edit', $rights, $middleware);
		self::get($url.'/show/{id}', $controller.'@show', $rights, $middleware);
		self::post($url.'/store', $controller.'@store', $rights, $middleware);
		self::post($url.'/update', $controller.'@update', $rights, $middleware);
		self::get($url.'/delete/{id}', $controller.'@delete', $rights, $middleware);
	}
    
    private static function match(string $as, string $route, string $method, int $rights, $middleware = null): void
    {
        $route = str_replace('@', '/', $route);
        $routes = explode('/', $route);
        
        self::$routes[self::$alias . $as ?? $route] = [
            'controller' => ucfirst($routes[0]),
            'action' => $routes[1] ?? 'index',
            'namespace' => self::$namespace,
            'method' => $method,
            'rights' => $rights,
            'middleware' => static::$middleware ?? $middleware
        ];
	
	    if ($method !== 'get') {
		    Csrf::make(ucfirst($routes[0]).'@'.$routes[1]);
	    }
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
    
    public static function back()
    {
	    self::redirect(Session::get('history')[0] ?? '/');
    }
    
    public static function checkProtocol(): string
    {
        return isset($_SERVER['HTTPS']) || $_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http';
    }
}
