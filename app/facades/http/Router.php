<?php

namespace App\Facades\Http;

use App\Core\Auth;
use App\Core\Kernel;
use App\Facades\Csrf\Csrf;
use App\Helpers\Session;
use ReflectionMethod;
use ReflectionClass;

final class Router extends Route
{
    private static array $params = [];
    
    private static ?string $provider = '';
    
    private static ?string $class = '';

    private static ?string $action = '';

    private static string $url = '';

    public object $request;
    
    private object $csrf;
    
    private static array $currentRoute = [];
    
    private static ?object $instance = null;

    public function __construct()
    {
        if (self::$instance === null) {
            self::$instance = $this;
        }
        
        $this->request = new Request();
        $this->csrf = new Csrf();
        $this->boot();
    }
    
    public static function getInstance(): Router
    {
        return self::$instance;
    }
	
	private function boot()
	{
		$this->parseUrl();
		$this->setParams();
		$this->request->sanitize();
		$this->runMiddlewares('before');
		
		if ($this->request->getMethod() === 'post') {
			if (! $this->csrf->valid($this->request)) {
				self::abort(400);
			}
		}
		
		$this->create(self::$provider . '\\' . self::getClass() . 'Controller');
		Session::history(self::$url);
		$this->runMiddlewares('after');
	}
    
    private function runMiddlewares(string $when): void
    {
        if (! empty(self::$currentRoute['middleware'])) {
            $middleware = Kernel::getMiddleware(self::$currentRoute['middleware']);
            if (method_exists($middleware, $when)) {
                (new $middleware())->$when($this->request, $this);
            }
        }
    
        if (! empty(Kernel::getEveryMiddleware())) {
            foreach (Kernel::getEveryMiddleware() as $middleware) {
                if (method_exists($middleware, $when)) {
                    (new $middleware())->$when($this->request, $this);
                }
            }
        }
    }
    
    public function getCurrentRoute(): array
    {
        return self::$currentRoute;
    }
    
    private function setCurrentRoute($route): void
    {
        self::$currentRoute = $route;
        self::setNamespace($route['namespace']);
        self::setClass($route['controller']);
        self::setAction($route['action']);
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
    
    public static function getAlias(): string
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
                
                if ($reflection->isProtected() || $reflection->isPrivate()) {
                    self::abort();
                }
                
                $controller = new $controller();
                $params = $reflection->getParameters();
                
                if (empty($params)) {
                    return $controller->{self::getAction()}();
                }
                
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
            $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $key);
            
            if (preg_match_all('#^' . $pattern . '$#', self::$url, $matches)) {
                if ((string) $this->request->getMethod() !== (string) $route['method']) {
                    self::abort(405);
                }
    
                $routeExist = true;
                $this->setCurrentRoute($route);
                
                $matches = array_slice($matches, 1);
	
	            if ((bool) strpos($matches[0][0], '/') === true) {
		            $matches = explode('/', $matches[0][0]);
		            foreach ($matches as $value) {
			            self::$params[] = $value;
		            }
	            } else {
		            foreach ($matches as $key2 => $value) {
			            self::$params[] = $matches[$key2][0];
		            }
	            }
                
                break;
            }
        }

        if (! $routeExist) {
            self::abort();
        }
        
        if (! empty (self::$params)) {
            $this->request->setData(self::$params);
        }
    }
    
    public static function url(): string
    {
        return getenv('REQUEST_URI');
    }
    
    private function parseUrl(): void
    {
        if ((string) app['url'] !== '/') {
            self::$url = str_replace(app['url'], '', self::url());
        } else {
            self::$url = self::url();
        }

        $this->setQueryStringParams();
        
        self::$url = preg_replace('/\?.*/',
            '',
            filter_var(self::$url, FILTER_SANITIZE_URL)
        );
        
        if ((string) self::$url !== '/') {
            self::$url = rtrim(self::$url, '/');
        }
    }
    
    private function setQueryStringParams()
    {
        parse_str(parse_url(self::$url)['query'], $str);
        
        if (! empty($str)) {
            foreach ($str as $key => $item){
                self::$params[$key] = $item;
            }
        }
    }
    
    private static function abort($code = 404): void
    {
        header("HTTP/1.1 {$code} Not Found");
        http_response_code($code);
        exit(require_once (view_path('errors/'.$code.'.php')));
    }
	
	public static function csrfPath(): string
	{
		return self::$currentRoute['controller'].'@'.self::$currentRoute['action'];
	}
}
