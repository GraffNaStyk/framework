<?php

namespace App\Facades\Http\Router;

use App\Controllers\Auth;
use App\Core\Kernel;
use App\Events\EventServiceProvider;
use App\Facades\Csrf\Csrf;
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

        if ($this->request->getMethod() === 'post' && ! defined('API')) {
            if (! $this->csrf->valid($this->request)) {
                self::abort(403);
            }
        }

        $this->create(self::$route->getNamespace().'\\'.self::getClass().'Controller');
        $this->runMiddlewares('after');
        $this->dispatchEvents();
    }

    private function dispatchEvents()
    {
        $events = EventServiceProvider::getListener(
            self::$route->getNamespace().'\\'.self::getClass().'Controller'
        )[self::getAction()];

        if (! empty($events)) {
            foreach ($events as $event) {
                (new $event)->handle();
            }
        }
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
        if (method_exists($controller, self::getAction())) {
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
                $reflection = new ReflectionMethod($controller, self::getAction());

                if ($reflection->isProtected() || $reflection->isPrivate()) {
                    Log::custom('router', ['msg' => 'Aborted by access to private or protected method']);
                    self::abort();
                }

	            $constructorParams = $this->reflectConstructorParams(
		            $reflectionClass->getConstructor()->getParameters()
	            );

                $controller = call_user_func_array([$reflectionClass, 'newInstance'], $constructorParams);
                $params     = $reflection->getParameters();
	            $paramCount = count($params);

                if (empty($params)) {
                    return $controller->{self::getAction()}();
                }

	            if ($reflection->getNumberOfRequiredParameters() > $paramCount) {
		            Log::custom('router', ['msg' => 'Not enough params']);
		            self::abort();
	            }

	            $combinedParams = $this->checkParamTypes($paramCount, $params, $controller);

                return call_user_func_array([$controller, self::getAction()], $combinedParams);

            } catch (\ReflectionException $e) {
                Log::custom('router', ['msg' => $e->getMessage(), 'line' => $e->getLine(), 'file' => $e->getFile()]);
                self::abort();
            }
        }

        self::abort();
    }

    private function reflectConstructorParams(array $reflectionParams): array
    {
	    $combinedParams = [];

		if (! empty($reflectionParams)) {
			foreach ($reflectionParams as $refParam) {
				if (! empty($class = $refParam->getClass()->name)) {
					$combinedParams[] = new $class;
				}
			}
		}

		return $combinedParams;
    }

    private function checkParamTypes(int $count, array $reflectionParams, object $controller): array
    {
	    $combinedParams = [];

        if (! empty($reflectionParams)) {
	        $requestParams  = $this->request->getData();

            try {
                for ($i = 0; $i < $count; $i ++) {
                    $refParam = new \ReflectionParameter([$controller, self::getAction()], $i);

                    if (! empty($class = $refParam->getClass()->name)) {
                    	if ($class === Request::class) {
		                    $combinedParams[$i] = $this->request;
	                    } else {
		                    $combinedParams[$i] = new $class;
	                    }

                    	continue;
                    }

                    if ($refParam->isOptional() && ! isset($requestParams[$i])) {
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
        if (! empty($matches)) {
            $matches = explode('/', $matches[0]);

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

    private function setQueryStringParams()
    {
        parse_str(parse_url(self::$url)['query'], $str);

        if (! empty($str)) {
            foreach ($str as $key => $item) {
                self::$params[$key] = $item;
            }
        }
    }

    private static function abort(int $code = 404, ?string $message = null): void
    {
        Log::custom('aborted', [
            'message' => 'Aborted operation from router, code: '.$code.' '.Header::RESPONSE_CODES[$code],
            'custom_msg' => $message,
            'user' => Auth::user()
        ]);

        header("HTTP/1.1 {$code} ".Header::RESPONSE_CODES[$code]);
        http_response_code($code);

        if ((API && defined('API')) || Request::isAjax()) {
            Response::json(['msg' => Header::RESPONSE_CODES[$code]], $code);
        } else {
            exit(require_once(view_path('errors/error.php')));
        }
    }

    public static function csrfPath(): string
    {
        return self::$route->getController().'@'.self::$route->getAction();
    }
}
