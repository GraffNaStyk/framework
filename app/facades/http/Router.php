<?php
namespace App\Facades\Http;

use App\Core\Auth;
use App\Helpers\Session;
use ReflectionMethod;
use ReflectionClass;
use App\Facades\Url\Url;

final class Router
{
    private static array $params = [];

    private static array $aliases = [];

    private static string $provider = '';
    
    private static ?string $baseRouteProvider = null;
    
    private static string $class = 'Index';

    private static string $action = 'index';

    private static array $routes = [];

    private static ?string $alias = null;

    private static string $url = '';

    private object $request;

    public function __construct()
    {
        self::$provider = app['http-provider'];
        $this->request = new Request();
        $this->parseUrl();
        $this->setParams();
        Session::set(['previous_url' => self::getClass().'/'.self::getAction()]);
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
        if (class_exists($controller) === true) {

            if(!method_exists($controller, self::getAction()))
                self::http404();
            
            $reflectionClass = new ReflectionClass($controller);

            if((string) $reflectionClass->getMethod(self::getAction())->class !== (string) $controller)
                self::http404();
            
            $reflection = new ReflectionMethod($controller, self::getAction());
            
            $controller = new $controller();

            $params = $reflection->getParameters();

            if (empty($params) === true)
                return $controller->{self::getAction()}();
            
            if (empty(self::$params) == false) {
                foreach (self::$params as $key => $param) {
                    $this->request->set($key, $param);
                }
            }

            $this->request->sanitize();
    
            if (isset($params[0]->name) && (string) $params[0]->name === 'request') {
                return $controller->{self::getAction()}($this->request);
            }
    
            if ($reflection->getNumberOfRequiredParameters() > count(self::$params))
                self::http404();
            
            return call_user_func_array([$controller, self::getAction()], $this->request->getData());
        }
        
        self::http404();
    }
    
    public function setParams()
    {
        $exist = false;

        foreach (self::$routes as $key => $route) {
            self::$params = [];
            $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $key);
            
            if (preg_match_all('#^' . $pattern . '$#', self::$url, $matches)) {

                if (! Auth::middleware($route['controller'], $route['rights'])) {
                    self::redirect(Url::base());
                }
                
                $exist = true;
                
                if ((string) $this->request->getMethod() !== (string) $route['method']) {
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
        if ((bool)$exist === false) {
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
            unset($route[0], $route[1]);
        
            foreach ($route as $key2 => $value)
                self::$params[] = $value;
        
            self::match('',
                self::getClass() . '@' . self::getAction(),
                getenv('REQUEST_METHOD'),
                4
            );
        }
    }

    public static function post(string $as, string $route, int $rights = 4): void
    {
        self::match($as, $route, 'post', $rights);
    }

    public static function get(string $as, string $route, int $rights = 4): void
    {
        self::match($as, $route, 'get', $rights);
    }
    
    private static function match(string $as, string $route, string $method, int $rights): void
    {
        $route = str_replace('@', '/', $route);
        $routes = explode('/', $route);
        self::$routes[$as ?? $route] = [
            'controller' => ucfirst($routes[0]),
            'action' => strtolower($routes[1]) ?? 'index',
            'method' => $method,
            'rights' => $rights
        ];
    }
    
    public static function redirect(string $path, int $code = 302, bool $direct = false): void
    {
        session_write_close();

        if ($direct) {
            header('location: '.self::checkProtocol().'://' . $_SERVER['HTTP_HOST'] . Url::base() . $path, true, $code);
        } else {
            header('location: '.self::checkProtocol().'://' . $_SERVER['HTTP_HOST'] . Url::get() . $path, true, $code);
        }
        exit;
    }
    
    public static function url(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
    
    private function parseUrl(): void
    {
        if((string) app['url'] !== '/') {
            self::$url = str_replace(app['url'], '', self::url());
        } else {
            self::$url = self::url();
        }
        
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

    public static function group(array $alias, callable $function): void
    {
        self::$aliases[$alias['prefix']] = [
            'ns' => str_replace('\\', '\\', $alias['as']) . '\\',
            'base' => $alias['base'] ?? null
        ];
        $function();
    }
}
