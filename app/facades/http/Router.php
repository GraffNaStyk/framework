<?php namespace App\Facades\Http;

use ReflectionMethod;
use ReflectionClass;
use App\Facades\Url\Url;

final class Router
{
    private static array $params = [];

    private static array $aliases = [];

    private static string $provider = 'App\\Controllers\\Http\\';
    
    private static ?string $baseRouteProvider = null;
    
    private static string $class = 'Index';

    private static string $action = 'index';

    private static array $routes = [];

    private static ?string $alias = null;

    private static string $url;

    private object $request;

    public function __construct()
    {
        $this->request = new Request();
        $this->parseUrl();
        $this->setParams();
        $this->create(self::$provider . self::getClass() . 'Controller');
    }

    public static function getClass(): string
    {
        return self::$class;
    }

    public static function getAction(): string
    {
        return self::$action;
    }

    private static function setClass(string $class): void
    {
        self::$class = ucfirst($class);
    }

    private static function setAction(string $action): void
    {
        self::$action = lcfirst($action);
    }

    public static function getAlias()
    {
        return self::$alias;
    }
    
    private function create(string $controller)
    {
        if (class_exists($controller)) {

            if(!method_exists($controller, self::getAction()))
                self::http404();
            
            $reflectionClass = new ReflectionClass($controller);

            if($reflectionClass->getMethod(self::getAction())->class != $controller)
                self::http404();
            
            $reflection = new ReflectionMethod($controller, self::getAction());
            
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

    private function sanitize(array $params):array
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
    
    public function setParams()
    {
        $exist = false;
        foreach (self::$routes as $key => $route) {
            $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $key);
            if (preg_match_all('#^' . $pattern . '$#', self::$url, $matches)) {
                $exist = true;
                if ((string) $this->request->getMethod() != (string) $route['method']) {
                    $this->http405();
                }

                self::setClass($route['controller']);
                self::setAction($route['action']);
                
                $matches = array_slice($matches, 1);
  
                foreach ($matches as $key2 => $value)
                    self::$params[] = $matches[$key2][0];
                
                break;
            }
        }
        self::setBasic($exist);
    }
    
    private static function setBasic(bool $exist): void
    {
        //this case is for automaticly routes like controller/action when
        if ((bool) $exist === false) {
            $route = explode('/', self::$url);
            if (self::$baseRouteProvider) {
                self::setClass(self::$baseRouteProvider);
            }
            else {
                if (!empty($route[0])) {
                    self::setClass($route[0]);
                }
            }
            
            if (isset($route[1]) && !empty($route[1])) {
                self::setAction($route[1]);
            }
        }
    }

    public static function post(string $as, string $route): void
    {
        self::match($as, $route, 'post');
    }

    public static function get(string $as, string $route): void
    {
        self::match($as, $route, 'get');
    }
    
    private static function match(string $as, string $route, $method): void
    {
        $route = str_replace('@', '/', $route);
        $routes = explode('/', $route);
        self::$routes[$as ?? $route] = [
            'controller' => ucfirst($routes[0]),
            'action' => $routes[1] ?? 'index',
            'method' => $method
        ];
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

    public static function url(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    private function parseUrl(): void
    {
        if(app['url'] != '/')
            self::$url = str_replace(app['url'], '', self::url());

        self::$url = preg_replace('/\?.*/', '', self::$url);
        
        foreach (self::$aliases as $key => $provider) {
            if(preg_match("/(^$key$|^$key(\?|\/))/U", self::$url, $m)) {
                $m = strtolower(rtrim($m[0], '/'));
                self::$url = preg_replace("/" . $m . "/", '', self::$url, 1);
                self::$provider = $provider['ns'];
                self::setClass($provider['base'] ?? 'Index');
                self::$alias = $m;
                break;
            }
        }
        self::$url = trim(self::$url, '/');
        self::$url = filter_var(self::$url, FILTER_SANITIZE_URL);
    }

    private static function checkProtocol(): string
    {
        return isset($_SERVER['HTTPS']) ? 'https' : 'http';
    }

    public static function http404(): void
    {
        header("HTTP/1.0 404 Not Found");
        http_response_code(404);
        exit(require_once (view_path('errors/404.php')));
    }
    
    private function http405(): void
    {
        header("HTTP/1.0 405 Method Not Allowed");
        http_response_code(405);
        exit(require_once (view_path('errors/405.php')));
    }

    public static function run(): self
    {
        return new self();
    }

    public static function group(array $alias, callable $function):void
    {
        self::$aliases[$alias['prefix']] = [
            'ns' => str_replace('.', '\\', $alias['as']) . '\\',
            'base' => $alias['base'] ?? null
        ];
        $function();
    }
}
