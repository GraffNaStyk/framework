<?php

namespace App\Facades\Http;

use App\Core\Auth;
use App\Core\Kernel;
use ReflectionMethod;
use ReflectionClass;

final class Router extends Route
{
    private static array $params = [];
    
    private static string $provider = 'Index';
    
    private static string $class = 'Index';

    private static string $action = 'index';

    private static string $url = '';

    private object $request;
    
    private static array $currentRoute = [];

    public function __construct()
    {
        $this->request = new Request();
        $this->boot();
    }
    
    private function boot()
    {
        $this->parseUrl();
        $this->setParams();
        $this->runMiddlewares('before');
        $this->create(self::$provider . '\\' . self::getClass() . 'Controller');
        $this->runMiddlewares('after');
    }
    
    private function runMiddlewares(string $when): void
    {
        if (! empty(self::$currentRoute['middleware'])) {
            $middleware = Kernel::getMiddleware(self::$currentRoute['middleware']);
            if (method_exists($middleware, $when)) {
                (new $middleware())->$when($this->request, self::$currentRoute);
            }
        }
    
        if (! empty(Kernel::getEveryMiddleware())) {
            foreach (Kernel::getEveryMiddleware() as $middleware) {
                if (method_exists($middleware, $when)) {
                    (new $middleware())->$when($this->request, self::$currentRoute);
                }
            }
        }
    }

    public static function getClass(): string
    {
        return self::$class;
    }

    public static function getAction(): string
    {
        return self::$action;
    }
    
    public static function getNamespace(): string
    {
        return self::$provider;
    }

    private static function setClass(string $class): void
    {
        self::$class = ucfirst($class);
    }

    private static function setAction(string $action): void
    {
        self::$action = lcfirst($action);
    }
    
    private static function setNamespace(string $namespace): void
    {
        self::$provider = $namespace;
    }
    
    public static function getAlias()
    {
        $alias = mb_strtolower(end(explode('\\', self::getNamespace())));
        
        if ($alias === 'http') {
            return 'http';
        }
        
        return 'admin';
    }
    
    private function create(string $controller)
    {
        if (class_exists($controller)) {
            if (! method_exists($controller, self::getAction())) {
                self::abort();
            }
            
            try {
                $reflectionClass = new ReflectionClass($controller);
                if ((string) $reflectionClass->getMethod(self::getAction())->class !== (string) $controller) {
                    self::abort();
                }
            } catch (\ReflectionException $e) {
                self::abort();
            }

            try {
                $reflection = new ReflectionMethod($controller, self::getAction());
                
                $controller = new $controller();
    
                $params = $reflection->getParameters();

                if (empty($params)) {
                    return $controller->{self::getAction()}();
                }
    
                $this->request->sanitize();
    
                if (isset($params[0]->name) && (string) $params[0]->name === 'request') {
                    return $controller->{self::getAction()}($this->request);
                }
    
                if ($reflection->getNumberOfRequiredParameters() > count(self::$params)) {
                    self::abort();
                }
    
                return call_user_func_array([$controller, self::getAction()], $this->request->getData());
            } catch (\ReflectionException $e) {
                self::abort();
            }
        } else {
            self::abort();
        }
    }
    
    public function setParams()
    {
        $routeExist = false;
        foreach (self::$routes as $key => $route) {
            self::$params = [];
            $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $key);
            
            if (preg_match_all('#^' . $pattern . '$#', self::$url, $matches)) {
                self::$currentRoute = $route;
                $routeExist = true;

                if ((string) $this->request->getMethod() !== (string) $route['method']) {
                    self::abort(405);
                }

                self::setNamespace($route['namespace']);
                self::setClass($route['controller']);
                self::setAction($route['action']);
                
                $matches = array_slice($matches, 1);
                
                foreach ($matches as $key2 => $value) {
                    self::$params[] = $matches[$key2][0];
                }
                
                break;
            }
        }

        if (! $routeExist) {
            self::abort();
        }
        
        if (! empty (self::$params)) {
            foreach (self::$params as $key => $param) {
                $this->request->set($key, $param);
            }
        }
    }
    
    public static function url(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
    
    private function parseUrl(): void
    {
        if ((string) app['url'] !== '/') {
            self::$url = str_replace(app['url'], '', self::url());
        } else {
            self::$url = self::url();
        }
        
        self::$url = filter_var(self::$url, FILTER_SANITIZE_URL);
    }
    
    private static function abort($code = 404): void
    {
        header("HTTP/1.0 404 Not Found");
        http_response_code($code);
        exit(require_once (view_path('errors/'.$code.'.php')));
    }

    public static function run(): self
    {
        return new self();
    }
}
