<?php

namespace App\Facades\Http\Router;

use App\Controllers\UserState;
use App\Core\Kernel;
use App\Events\EventServiceProvider;
use App\Facades\Config\Config;
use App\Facades\Csrf\Csrf;
use App\Facades\Dependency\Container;
use App\Facades\Dependency\ContainerBuilder;
use App\Facades\Devtool\DevTool;
use App\Facades\Header\Header;
use App\Facades\Http\Request;
use App\Facades\Http\Response;
use App\Facades\Http\View;
use App\Facades\Log\Log;
use ReflectionClass;
use ReflectionMethod;

final class Router extends Route
{
    private static array $params = [];

    private static ?string $url = null;

    public Request $request;

    private Csrf $csrf;
    
    private ContainerBuilder $builder;

    private static ?Collection $route = null;

    private static ?Router $instance = null;

    public function __construct()
    {
        if (self::$instance !== null) {
            throw new \LogicException('Cannot load router two times');
        }
	
	    self::$instance = $this;
        $this->request  = new Request();

        if ($this->request->isOptionsCall()) {
            return;
        }
	
	    $this->builder = new ContainerBuilder(new Container());
	    $this->csrf    = new Csrf();
	    $this->builder->container->add(Request::class, $this->request);
    }

    public static function getInstance(): Router
    {
        return self::$instance;
    }
    
    public function getContainer(): ContainerBuilder
    {
    	return $this->builder;
    }

    public function boot(): void
    {
        $this->parseUrl();
        $this->setParams();
        $this->request->sanitize();
        $this->runMiddlewares('before');

        if ($this->request->getMethod() === 'post' && ! defined('API')) {
            if (! $this->csrf->valid($this->request)) {
                self::abort(403);
            }
        }

        $this->create(self::$route->getNamespace().'\\'.self::getClass().'Controller');

        if (in_array(http_response_code(), [200,201], true)) {
            $this->runMiddlewares('after');
            $this->dispatchEvents();
        }
    }

    private function dispatchEvents(): void
    {
        $events = EventServiceProvider::getListener(
            self::$route->getNamespace().'\\'.self::getClass().'Controller'
        );
		
        foreach ($events[self::getAction()] as $event) {
	        $reflector = new ReflectionClass($event);
	        (call_user_func_array([$reflector, 'newInstance'], $this->builder->getConstructorParameters($reflector)))->handle();
        }
    }

    private function runMiddlewares(string $when): void
    {
    	$path = Config::get('app.middleware_path');
	
	    foreach (self::$route->getMiddleware() as $middleware) {
		    $middleware = $path . ucfirst($middleware);
		
		    if (method_exists($middleware, $when)) {
			    $reflector = new ReflectionClass($middleware);
			    (call_user_func_array([$reflector, 'newInstance'], $this->builder->getConstructorParameters($reflector)))
				    ->$when($this->request, $this);
		    }
	    }
	
	    foreach (Kernel::getEveryMiddleware() as $middleware) {
		    if (method_exists($middleware, $when)) {
			    $reflector = new ReflectionClass($middleware);
			    (call_user_func_array([$reflector, 'newInstance'], $this->builder->getConstructorParameters($reflector)))
				    ->$when($this->request, $this);
		    }
	    }
    }

    public function getCurrentRoute(): Collection
    {
    	if (! self::$route instanceof Collection) {
    		throw new \LogicException('$route must be a instance of '.Collection::class);
	    }
    	
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
            'method'      => self::$route->getMethod(),
            'params'      => $this->request->getData()
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

        return $alias === 'http' ? $alias : 'admin';
    }

    private function create(string $controller)
    {
        if (! class_exists($controller) || ! method_exists($controller, self::getAction())) {
            self::abort();
        }

        try {
            $reflectionClass = new ReflectionClass($controller);
	
	        if ((string) $reflectionClass->getMethod(self::getAction())->class !== (string) $controller) {
		        self::abort();
	        }
        } catch (\ReflectionException $e) {
            Log::custom('router', ['msg' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
            self::abort();
        }

        try {
        	if (substr(self::getAction(), 0, 4) === 'test' && ! Config::get('app.dev')) {
        		self::abort();
	        }

            $reflectionMethod = new ReflectionMethod($controller, self::getAction());
	
	        if ($reflectionMethod->isProtected() || $reflectionMethod->isPrivate()) {
		        Log::custom('router', ['msg' => 'Aborted by access to private or protected method']);
		        self::abort();
	        }

            if ($reflectionMethod->getReturnType() === null) {
                throw new \LogicException('Method must have a return type declaration');
            }

            $constructorParams = $this->builder->getConstructorParameters($reflectionClass);
	        $params            = $reflectionMethod->getParameters();
	        $controller        = call_user_func_array([$reflectionClass, 'newInstance'], $constructorParams);
	        $methodParams      = $this->getMethodParams($params, $controller);

	        if ($reflectionMethod->getNumberOfRequiredParameters() > count($methodParams)) {
		        Log::custom('router', ['msg' => 'Not enough params']);
		        self::abort();
	        }
	
	        unset($reflectionMethod, $reflectionClass, $params, $constructorParams);
	
	        ob_flush();
	        ob_clean();
	
	        if (Config::get('app.dev')) {
		        View::set(['devTool' => DevTool::boot()]);
	        }
	
	        echo call_user_func_array(
		        [$controller, self::getAction()],
		        $methodParams
	        );
	
	        ob_end_flush();
	        ob_end_clean();
	        return;
        } catch (\ReflectionException $e) {
            Log::custom('router', ['msg' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
            self::abort();
        }
    }

    private function getMethodParams(array $reflectionParams, object $controller): array
    {
        $combinedParams   = [];
        $requestParams    = $this->request->getData();
        $reqParamIterator = 0;

        foreach ($reflectionParams as $key => $param) {
	        $refParam = new \ReflectionParameter([$controller, self::getAction()], $key);
	        $class    = $refParam->getClass()->name;

	        if (! empty($class)) {
		        $reflector = $this->builder->checkIsInterface(new ReflectionClass($class));

		        if ($reflector->hasMethod('__construct')) {
			        $params = $this->builder->reflectConstructorParams($reflector->getConstructor()->getParameters());
			        $this->builder->container->add($class, call_user_func_array([$reflector, 'newInstance'], $params ?? []));
		        } else {
			        $this->builder->container->add($class, new $class());
		        }

		        $combinedParams[$key] = $this->builder->container->get($class);
		        unset($reflector, $refParam, $reflectionParams[$key]);
	        } else {
		        if ($refParam->isOptional() && ! isset($requestParams[$reqParamIterator])) {
			        unset($refParam, $reflector);
			        $reqParamIterator++;
			        continue;
		        }
		
		        $type = preg_replace(
			        '/.*?(\w+)\s+\$' . $refParam->name . '.*/',
			        '\\1',
			        $refParam->__toString()
		        );
		
		        if ($type === 'int') {
			        $type = 'integer';
		        }
		
		        if ($type === 'float') {
			        $type = 'double';
		        }
		
		        if (gettype($requestParams[$reqParamIterator]) !== $type) {
			        self::abort(400, 'Wrong param type, param: ' . $requestParams[$reqParamIterator]);
		        }
		
		        $combinedParams[$key] = $requestParams[$reqParamIterator];
		        $reqParamIterator++;
		        unset($refParam, $reflector);
	        }
        }

        return $combinedParams;
    }

    public function setParams(): void
    {
        $routeExist = false;

        foreach (self::$routes as $key => $route) {
            $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $key);

            if (preg_match('#^'.$pattern.'$#', self::$url, $matches)) {
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
		if (strpos($matches[0][0], '/') !== false) {
			$matches = explode('/', $matches[0][0]);
		}
		
		foreach ($matches as $value) {
			self::$params[] = $value;
		}
	}

    public static function url(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    private function parseUrl(): void
    {
        if ((string) Config::get('app.url') !== '/') {
            self::$url = str_replace(Config::get('app.url'), '', self::url());
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

    private function setQueryStringParams(): void
    {
        parse_str(parse_url(self::$url)['query'], $str);

        if (! empty($str)) {
            foreach ($str as $key => $item) {
                self::$params[$key] = $item;
            }
        }
    }

    public static function abort(int $code = 404, ?string $message = null): void
    {
        Log::custom('aborted', [
            'message' => 'Aborted operation from router, code: '.$code.' '.Header::RESPONSE_CODES[$code],
            'custom_msg' => $message,
            'user' => UserState::user()
        ]);

        header("HTTP/1.1 {$code} ".Header::RESPONSE_CODES[$code]);
        http_response_code($code);

        if ((API && defined('API')) || Request::isAjax()) {
            Response::jsonWithForceExit(['msg' => Header::RESPONSE_CODES[$code]], $code);
        } else {
            exit(require_once(view_path('errors/error.php')));
        }
    }

    public static function csrfPath(): string
    {
        return self::$route->getController().'@'.self::$route->getAction();
    }
}
