<?php namespace App\Core;

use ReflectionMethod;

class Router
{
    private static $params = [];

    private static $http = 'App\\Controllers\\Http\\';

    private static $cms = 'App\\Controllers\\Admin\\';

    private static $admin = false;

    private static $class = null;

    private static $action = null;

    private static $routes = [];

    private static $url = null;

    private static $routerUrl = null;

    private $requestMethod;

    private $request;

    private static $defaultController;

    public function __construct()
    {
        $this->request = new Request();
        $this->setRequestMethod();
        $this->setRoutes();
        $controller = !self::$admin ? self::$http : self::$cms;
        $this->create($controller .= ucfirst(self::$class) . 'Controller');
    }

    public static function getClass(): string
    {
        return self::$class;
    }

    public static function getAction(): string
    {
        return self::$action;
    }

    public static function setClass(string $class): void
    {
        self::$class = ucfirst($class);
    }

    public static function setAction(string $action): void
    {
        self::$action = lcfirst($action);
    }

    private function setRequestMethod(): void
    {
        $this->requestMethod = $this->request->getMethod();
    }

    public static function isAdmin(): string
    {
        return self::$admin;
    }

    private function create(string $controller)
     {
        if (class_exists($controller) && method_exists($controller, self::$action)) {

            $reflection = new ReflectionMethod($controller, self::$action);
            $params = $reflection->getParameters();

            $controller = new $controller();

            if (empty($params))
                return $controller->{self::$action}();

            if (isset($params[0]->name) && $params[0]->name == 'request')
                return $controller->{self::$action}($this->request);

            if ($reflection->getNumberOfRequiredParameters() != count(self::$params))
                self::http404();

            return call_user_func_array([$controller, self::$action], self::$params);
        }

        self::redirect('Index/http404');
    }

    private function setRoutes(): array
    {
        $routes = [];
        $flagToRemoveRoute = true;
        if (isset($_SERVER['REQUEST_URI']) && !empty($_SERVER['REQUEST_URI'])) {

            $this->parseUrl();

            if (!empty(self::$routes) && array_key_exists(self::$routerUrl, self::$routes)) {
                $routes = explode('/', self::$routes[self::$routerUrl][0]);
            } else $routes = explode('/', self::$routerUrl);

            if (in_array('admin', $routes)) {
                self::$admin = true;
                array_shift($routes);
                static::defaultController('Login');
            }

            if (!empty($routes) && array_key_exists($routes[0], self::$routes) && !self::$admin) {
                $flagToRemoveRoute = false;
                $urlParams = explode('/', self::$routes[$routes[0]][0]);
                self::$class = $urlParams[0];
                self::$action = lcfirst($urlParams[1]);
            } else {

                if (isset($routes[0]) && !empty($routes[0]))
                    self::$class = ucfirst($routes[0]);
                else
                    self::$class = ucfirst(self::$defaultController);

                if (isset($routes[1]) && !empty($routes[1])) {
                    if (strpos($routes[1], '?') !== false) {
                        $route = explode('?', $routes[1]);
                        self::$action = lcfirst($route[0]);
                    } else self::$action = lcfirst($routes[1]);
                } else self::setAction('index');
            }

        } else {
            self::$url = self::$defaultController . '/index';
            self::setClass(self::$defaultController);
            self::setAction('index');
        }

        if (isset($routes[0]))
            unset($routes[0]);

        if (isset($routes[1]) && $flagToRemoveRoute)
            unset($routes[1]);

        $this->checkIsRequestMethodProvided();

        return self::$params = array_filter($routes, 'strlen');
    }

    public static function post(string $route, string $as = null): void
    {
        static::$routes[$as ?? $route] = [$route, 'post'];
    }

    public static function get(string $route, string $as = null): void
    {
        static::$routes[$as ?? $route] = [$route, 'get'];
    }

    private function checkIsRequestMethodProvided(): void
    {
        $url = self::$routerUrl ?? static::$defaultController;

        if (isset(static::$routes[$url]))
            if (static::$routes[$url][1] != $this->requestMethod)
                $this->methodAllowedException();

        if (is_array(static::$routes)) {
            foreach (static::$routes as $key => $routeParams) {
                if ($routeParams[0] === static::$class . '/' . static::$action || $routeParams[0] === static::$class)
                    if ($routeParams[1] != $this->requestMethod)
                        $this->methodAllowedException();
            }
        }
    }

    public static function defaultController(string $controller = 'Index'): void
    {
        self::$defaultController = ucfirst($controller);
    }

    private function methodAllowedException(): void
    {
        exit("This route is not supported by this method");
    }

    public static function redirect(string $path, int $code = 302): void
    {
        session_write_close();
        session_start();

        header('location: '.self::checkProtocol().'://' . $_SERVER['HTTP_HOST'] . Url::get() . $path, true, $code);

        exit;
    }

    public static function debug(): void
    {
        print_r(['class' => self::getClass(), 'action' => self::getAction(), 'routes' => self::$routes]);
    }

    public static function url(): string
    {
        return self::$url;
    }

    private function parseUrl(): void
    {
        self::$url = $_SERVER['REQUEST_URI'];

        self::$routerUrl = str_replace(app['url'], '', $_SERVER['REQUEST_URI']);
        self::$routerUrl = rtrim(self::$routerUrl, '/');
        self::$routerUrl = ltrim(self::$routerUrl, '/');
        self::$routerUrl = filter_var(self::$routerUrl, FILTER_SANITIZE_URL);
        self::$routerUrl = urldecode(self::$routerUrl);
    }

    private static function checkProtocol(): string
    {
        return isset($_SERVER['HTTPS']) ? 'https' : 'http';
    }

    public static function http404(): void
    {
        static::redirect('Index/http404');
    }

    public static function run(): self
    {
        return new self();
    }
}
