<?php

namespace App\Facades\Http\Router;

use App\Core\Kernel;
use App\Facades\Csrf\Csrf;
use App\Facades\Http\Request;
use App\Facades\Log\Log;
use ReflectionClass;
use ReflectionMethod;

final class Router extends Route
{
    private static array $params = [];
    
    private static ?string $url = null;

    public Request $request;
    
    private Csrf $csrf;
    
    private static ?Collection $route = null;
    
    private static ?Router $instance = null;

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

		$this->create(self::$route->getNamespace() . '\\' . self::getClass() . 'Controller');
		$this->runMiddlewares('after');
	}
    
    private function runMiddlewares(string $when): void
    {
        if (self::$route->getMiddleware() !== null) {
            $middlewares = Kernel::getMiddlewares(self::$route->getMiddleware());

            foreach ($middlewares as $middleware) {
	            if (method_exists($middleware, $when)) {
		            (new $middleware())->$when($this->request, $this);
	            }
            }
        }
    
        $everyMiddlewares = Kernel::getEveryMiddleware();
        
        if (! empty($everyMiddlewares)) {
            foreach ($everyMiddlewares as $middleware) {
                if (method_exists($middleware, $when)) {
                    (new $middleware())->$when($this->request, $this);
                }
            }
        }
    }
    
    public function getCurrentRoute(): Collection
    {
        return self::$route;
    }
    
    public function routeParams(): array
    {
    	return [
    		'controller'  => self::getClass(),
		    'action'      => self::getAction(),
		    'namespace'   => self::$route->getNamespace(),
		    'rights'      => self::$route->getRights(),
		    'middlewares' => self::$route->getMiddleware(),
		    'method'      => self::$route->getMethod()
	    ];
    }
    
    private function setCurrentRoute(Collection $route): void
    {
        self::$route = $route;
    }

    public static function getClass(): string
    {
        return self::$route->getController();
    }

    public static function getAction(): string
    {
        return self::$route->getAction();
    }
    
    public static function getNamespace(): ?string
    {
    	if (self::$route instanceof Collection) {
    		return self::$route->getNamespace();
	    }
    	
    	return null;
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
        if (method_exists($controller, self::getAction())) {
            try {
                $reflectionClass = new ReflectionClass($controller);
                
                if ((string) $reflectionClass->getMethod(self::getAction())->class !== (string) $controller) {
                    self::abort();
                }
            } catch (\ReflectionException $e) {
            	Log::custom('router', ['msg' => $e->getMessage()]);
                self::abort();
            }

            try {
                $reflection = new ReflectionMethod($controller, self::getAction());
                
                if ($reflection->isProtected() || $reflection->isPrivate()) {
	                Log::custom('router', ['msg' => 'Aborted by access to private or protected method']);
                    self::abort();
                }
                
                $controller = new $controller();
                $params = $reflection->getParameters();
                
                if (empty($params)) {
                    return $controller->{self::getAction()}();
                }

                if ((string) $params[0]->name === 'request' && ! empty($this->request->all())) {
                    return $controller->{self::getAction()}($this->request);
                } else if ((string) $params[0]->name === 'request' && empty($this->request->all())) {
	                Log::custom('router', ['msg' => 'Trying to access with empty request']);
                	self::abort(403);
                }

                if ($reflection->getNumberOfRequiredParameters() > count(self::$params)) {
	                Log::custom('router', ['msg' => 'Not enough params']);
                    self::abort();
                }
    
                return call_user_func_array([$controller, self::getAction()], $this->request->getData());
                
            } catch (\ReflectionException $e) {
	            Log::custom('router', ['msg' => $e->getMessage()]);
                self::abort();
            }
        }
        
	    self::abort();
    }
    
    public function setParams(): void
    {
        $routeExist = false;
        
        foreach (self::$routes as $key => $route) {
            $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $key);
            
            if (preg_match_all('#^' . $pattern . '$#', self::$url, $matches)) {
                if ((string) $this->request->getMethod() !== (string) $route->getMethod()) {
                    self::abort(405);
                }

                $routeExist = true;
                $this->setCurrentRoute($route);
                $this->setMatches(array_slice($matches, 1));
                break;
            }
        }

        if (! $routeExist) {
            self::abort();
        }
        
        if (! empty(self::$params)) {
            $this->request->setData(self::$params);
        }
    }
    
    private function setMatches(array $matches): void
    {
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
    }
    
    public static function url(): string
    {
        return getenv('REQUEST_URI');
    }
    
    private function parseUrl(): void
    {
        if ((string) app('url') !== '/') {
            self::$url = str_replace(app('url'), '', self::url());
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
            foreach ($str as $key => $item) {
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
		return self::$route->getController().'@'.self::$route->getAction();
	}
}
