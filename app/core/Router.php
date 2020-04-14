<?php namespace App\Core;

use App\Helpers\Session;
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

    public function __construct()
    {
        self::setClass(app['controller']);
        self::setAction(app['controller']);

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
        if (class_exists($controller) && method_exists($controller, self::getAction())) {

            $reflection = new \ReflectionMethod($controller, self::getAction());

            $controller = new $controller();

            $params = $reflection->getParameters();

            if (empty($params))
                return $controller->{self::getAction()}();

            if (isset($params[0]->name) && $params[0]->name == 'request')
                return $controller->{self::getAction()}($this->request);

            if ($reflection->getNumberOfRequiredParameters() != count(self::$params))
                self::http404();

            return call_user_func_array([$controller, self::getAction()], $this->sanitize(self::$params));
        }

         self::http404();
    }

    private function sanitize($params)
    {
        foreach ($params as $key => $param) {
            if(!is_null($param))
                $param = trim($param);

           if(!is_numeric($param))
               $param = urldecode($param);

            $params[$key] = $param;
        }

        return $params;
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

            if (!empty($routes) && array_key_exists($routes[0], self::$routes)) {
                $flagToRemoveRoute = false;
                $urlParams = explode('/', self::$routes[$routes[0]][0]);
                self::setClass($urlParams[0]);
                self::setAction(lcfirst($urlParams[1]));

            } else {

                if (isset($routes[0]) && !empty($routes[0]))
                    self::setClass(ucfirst($routes[0]));

                if (isset($routes[1]) && !empty($routes[1]))
                  self::setAction(lcfirst($routes[1]));
            }
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
        if (isset(static::$routes[self::$routerUrl]))
            if (static::$routes[self::$routerUrl][1] != $this->requestMethod)
                $this->methodAllowedException();

        if (is_array(static::$routes)) {
            foreach (static::$routes as $key => $routeParams) {
                if (ucfirst($routeParams[0]) === static::getClass() . '/' . static::getAction() || ucfirst($routeParams[0]) === static::getClass())
                    if ($routeParams[1] != $this->requestMethod)
                        $this->methodAllowedException();
            }
        }
    }

    private function methodAllowedException(): void
    {
        exit("Sometimes went wrong...");
    }

    public static function redirect(string $path, int $code = 302, bool $direct = false): void
    {
        session_write_close();
        session_start();

        if($direct)
            header('location: '.self::checkProtocol().'://' . $_SERVER['HTTP_HOST'] . Url::base() . $path, true, $code);
        else
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

        if(app['url'] != '/')
            self::$routerUrl = str_replace(app['url'], '', $_SERVER['REQUEST_URI']);
        else
            self::$routerUrl = $_SERVER['REQUEST_URI'];

        //now we remove all query get string
        self::$routerUrl = preg_replace('/\?.*/', '', self::$routerUrl);

        //check if is calling admin panel
        if(preg_match("/".app['cms']."/", self::$routerUrl)) {
            self::$admin = true;
            static::setClass('dash');
            self::$routerUrl = preg_replace("/".app['cms']."/", '', self::$routerUrl, 1);
        }

        self::$routerUrl = trim(self::$routerUrl);
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
        self::$admin = false;
        static::redirect('index/http404');
    }

    public static function run(): self
    {
        return new self();
    }
}
