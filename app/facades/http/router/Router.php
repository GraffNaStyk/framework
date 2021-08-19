<?php

namespace App\Facades\Http\Router;

use App\Controllers\Auth;
use App\Core\Kernel;
use App\Events\EventServiceProvider;
use App\Facades\Csrf\Csrf;
use App\Facades\Dependency\Container;
use App\Facades\Header\Header;
use App\Facades\Http\Request;
use App\Facades\Http\Response;
use App\Facades\Log\Log;
use ReflectionClass;
use ReflectionMethod;

final class Router extends Route
{
    private static array $params = [];

    private static ?string $url = null;

    public Request $request;

    private Csrf $csrf;
    
    private Container $container;

    private static ?Collection $route = null;

    private static ?Router $instance = null;

    public function __construct()
    {
        if (self::$instance === null) {
            self::$instance = $this;
        }

        $this->container = new Container();
        $this->request   = new Request();

        if ($this->request->isOptionsCall()) {
            return;
        }

        $this->csrf = new Csrf();
        $this->container->add(Request::class, $this->request);
        $this->boot();
    }

    public static function getInstance(): Router
    {
        return self::$instance;
    }

    private function boot(): void
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
        )[self::getAction()];

        foreach ($events as $event) {
            (new $event)->handle($this->request);
        }
    }

    private function runMiddlewares(string $when): void
    {
        $middlewarePath = '\\App\\Controllers\\Middleware\\';

        foreach (self::$route->getMiddleware() as $middleware) {
            $middleware = $middlewarePath.ucfirst($middleware);
            if (method_exists($middleware, $when)) {
                (new $middleware())->$when($this->request, $this);
            }
        }

        foreach (Kernel::getEveryMiddleware() as $middleware) {
            if (method_exists($middleware, $when)) {
                (new $middleware())->$when($this->request, $this);
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
            'controller' => self::getClass(),
            'action' => self::getAction(),
            'namespace' => self::$route->getNamespace(),
            'rights' => self::$route->getRights(),
            'middlewares' => self::$route->getMiddleware(),
            'method' => self::$route->getMethod(),
            'params' => $this->request->getData()
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
        	if (substr(self::getAction(), 0, 4) === 'test' && !app('dev')) {
        		self::abort();
	        }

            $reflectionMethod = new ReflectionMethod($controller, self::getAction());

            if ($reflectionMethod->isProtected() || $reflectionMethod->isPrivate()) {
                Log::custom('router', ['msg' => 'Aborted by access to private or protected method']);
                self::abort();
            }

            if ($reflectionMethod->getReturnType() === null) {
                throw new \Exception('Method must have a return type declaration');
            }

            $constructorParams = [];

            if ($reflectionClass->hasMethod('__construct')) {
                $constructorParams = $this->reflectConstructorParams(
                    $reflectionClass->getConstructor()->getParameters()
                );
            }
	
	        $params       = $reflectionMethod->getParameters();
	        $controller   = call_user_func_array([$reflectionClass, 'newInstance'], $constructorParams);
	        $methodParams = $this->getMethodParams(count($params), $params, $controller);
	
	        if ($reflectionMethod->getNumberOfRequiredParameters() > count($methodParams)) {
		        Log::custom('router', ['msg' => 'Not enough params']);
		        self::abort();
	        }
	
	        unset($reflectionMethod, $reflectionClass, $params, $constructorParams);
	
	        ob_flush();
	        ob_clean();

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

    private function reflectConstructorParams(array $reflectionParams): array
    {
        $combinedParams = [];

        foreach ($reflectionParams as $refParam) {
            if (! empty($class = $refParam->getClass()->name)) {
	            $reflector = new ReflectionClass($class);
	
	            if (! $this->container->has($class)) {
		            if ($reflector->hasMethod('__construct')) {
			            $this->container->add($class,  call_user_func_array(
				            [$reflector, 'newInstance'],
				            $this->reflectConstructorParams($reflector->getConstructor()->getParameters())
			            ));
		            } else {
		            	$this->container->add($class, new $class());
		            }
	            }

	            $combinedParams[] = $this->container->get($class);
	            unset($reflector);
            }
        }

        return $combinedParams;
    }

    private function getMethodParams(int $count, array $reflectionParams, object $controller): array
    {
        $combinedParams = [];

        if (! empty($reflectionParams)) {
            $requestParams = $this->request->getData();

            try {
                for ($i = 0; $i < $count; $i++) {
                    $refParam = new \ReflectionParameter([$controller, self::getAction()], $i);

                    if (! empty($class = $refParam->getClass()->name)) {
	                    $reflector = new ReflectionClass($class);

                    	if (! $this->container->has($class)) {
		                    if ($reflector->hasMethod('__construct')) {
			                    $params = $this->reflectConstructorParams($reflector->getConstructor()->getParameters());
		                    }
	                    }

	                    $this->container->add($class, call_user_func_array([$reflector, 'newInstance'], $params ?? []));
	                    $combinedParams[$i] = $this->container->get($class);
	                    unset($reflector);
                        unset($refParam);
                        continue;
                    }

                    if ($refParam->isOptional() && ! isset($requestParams[$i])) {
                        unset($refParam);
                        continue;
                    }

                    $type = preg_replace(
                        '/.*?(\w+)\s+\$'.$refParam->name.'.*/',
                        '\\1',
                        $refParam->__toString()
                    );

                    if ($type === 'int') {
                        $type = 'integer';
                    }

                    if ($type === 'float') {
                        $type = 'double';
                    }

                    if (gettype($requestParams[$i]) !== $type) {
                        self::abort(400, 'Wrong param type, param: '.$requestParams[$i]);
                    }

                    $combinedParams[$i] = $requestParams[$i];

                    unset($refParam);
                }
            } catch (\ReflectionException $e) {
                Log::custom('router', ['msg' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
                self::abort();
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
		if ((bool) strpos($matches[0][0], '/') === true) {
			$matches = explode('/', $matches[0][0]);
			foreach ($matches as $value) {
				self::$params[] = $value;
			}
		} else {
			foreach ($matches as $value) {
				self::$params[] = $value;
			}
		}
	}

    public static function url(): string
    {
        return $_SERVER['REQUEST_URI'];
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
            'user' => Auth::user()
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
