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
            echo (new Response())->send()->getResponse();
            die;
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
	    $this->dispatchEvents('before');
	    
        if ($this->request->getMethod() === 'post' && ! Config::get('app.enable_api')) {
            if (! $this->csrf->valid($this->request)) {
                self::abort(403);
            }
        }
    }
    
    public function resolveRequest(): void
    {
	    $this->create(self::$route->getNamespace().'\\'.self::getClass().'Controller');
	
	    if (in_array(http_response_code(), [200,201], true)) {
		    $this->runMiddlewares('after');
		    $this->dispatchEvents('after');
	    }
    }

    private function dispatchEvents(string $when): void
    {
        $events = EventServiceProvider::getListener(
        	$when,
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
		    $middleware = $path.ucfirst($middleware);
		
		    if (method_exists($middleware, $when)) {
			    $reflector = new ReflectionClass($middleware);
			    (call_user_func_array([$reflector, 'newInstance'], $this->builder->getConstructorParameters($reflector)))
				    ->$when($this->request, $this);
		    }
	    }
	
	    foreach (Config::get('middleware') as $middleware) {
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
    		throw new \LogicException('self::$route must be a instance of '.Collection::class);
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
	    return self::$route instanceof Collection ? self::$route->getNamespace() : null;
    }

    public static function getAlias(): string
    {
        return mb_strtolower(end(explode('\\', self::getNamespace()))) === 'http' ? 'http' : 'admin';
    }

    private function create(string $controller)
    {
        if (! class_exists($controller) || ! method_exists($controller, self::getAction())) {
            self::abort();
        }
	
	    $reflectionClass = new ReflectionClass($controller);
	
	    if ((string) $reflectionClass->getMethod(self::getAction())->class !== (string) $controller) {
		    self::abort();
		    throw new \ReflectionException('Controller not exist : '. $controller);
	    }

        if (substr(self::getAction(), 0, 4) === 'test' && ! Config::get('app.dev')) {
            throw new \LogicException('Cannot read test method if env is set to production');
        }

        $reflectionMethod = new ReflectionMethod($controller, self::getAction());

        if ($reflectionMethod->isProtected() || $reflectionMethod->isPrivate()) {
	        throw new \LogicException('Cannot make private or protected methods in controller');
        }

        if ($reflectionMethod->getReturnType() === null) {
            throw new \LogicException('Method must have a return type declaration');
        }
        
        if ($reflectionMethod->getReturnType()->getName() !== Response::class) {
            throw new \LogicException('Controller return type declaration mus be a instance of '.Response::class);
        }

        $constructorParams = $this->builder->getConstructorParameters($reflectionClass);
        $params            = $reflectionMethod->getParameters();
        $controller        = call_user_func_array([$reflectionClass, 'newInstance'], $constructorParams);
        $methodParams      = $this->getMethodParams($params, $controller);

        if ($reflectionMethod->getNumberOfRequiredParameters() > count($methodParams)) {
	        throw new \LogicException('Not enough params');
        }

        unset($reflectionMethod, $reflectionClass, $params, $constructorParams);

        ob_flush();
        ob_clean();

        if (Config::get('app.dev')) {
	        View::set(['devTool' => DevTool::boot()]);
        }

        $response = call_user_func_array(
	        [$controller, self::getAction()],
	        $methodParams
        );

        echo $response->getResponse();

        ob_end_flush();
        ob_end_clean();
        return;
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
	        $pattern = preg_replace('/{(.*?)}/', '(.*?)', $key, -1);

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
            'message'    => 'Aborted operation from router, code: '.$code.' '.Response::RESPONSE_CODES[$code],
            'custom_msg' => $message,
            'user'       => UserState::user()
        ]);

        if (Config::get('app.enable_api') || Request::isAjax()) {
	        echo (new Response())->json()->setData(['msg' => Response::RESPONSE_CODES[$code]])->setCode($code)->getResponse();
            exit;
        } else {
            exit(require_once(view_path('errors/error.php')));
        }
    }

    public static function csrfPath(): string
    {
        return self::$route->getController().'@'.self::$route->getAction();
    }
}
