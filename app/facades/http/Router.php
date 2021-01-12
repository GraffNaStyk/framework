<?php
namespace App\Facades\Http;

use App\Core\Auth;
use App\Helpers\Session;
use ReflectionMethod;
use ReflectionClass;
use App\Facades\Url\Url;

final class Router extends Route
{
    private static array $params = [];
    
    private static string $provider = 'Index';
    
    private static string $class = 'Index';

    private static string $action = 'index';

    private static string $url = '';

    private object $request;

    public function __construct()
    {
        $this->request = new Request();
        $this->boot();
    }
    
    private function boot()
    {
        $this->parseUrl();
        $this->setParams();
        $this->create(self::$provider . '\\' . self::getClass() . 'Controller');
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
    
    public static function getAlias()
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
                self::http404();
            }
            
            $reflectionClass = new ReflectionClass($controller);

            if ((string) $reflectionClass->getMethod(self::getAction())->class !== (string) $controller) {
                self::http404();
            }
            
            $reflection = new ReflectionMethod($controller, self::getAction());
            
            $controller = new $controller();

            $params = $reflection->getParameters();

            if (empty($params))
                return $controller->{self::getAction()}();
    
            if (!empty(self::$params)) {
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
        foreach (self::$routes as $key => $route) {
            self::$params = [];
            $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $key);
            
            if (preg_match_all('#^' . $pattern . '$#', self::$url, $matches)) {
                if (! Auth::middleware($route['controller'], $route['action'], $route['rights'])) {
                    self::redirect(Url::base());
                }
                
                if ((string) $this->request->getMethod() !== (string) $route['method']) {
                    $this->http405();
                }
                
                self::setNamespace($route['namespace']);
                self::setClass($route['controller']);
                self::setAction($route['action']);
                
                $matches = array_slice($matches, 1);
                
                foreach ($matches as $key2 => $value) {
                    self::$params[] = $matches[$key2][0];
                }
                
                break;
            }
        }
    }
    
    public static function url(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
    
    private function parseUrl(): void
    {
        if ((string) app['url'] !== '/') {
            self::$url = str_replace(app['url'], '', self::url());
        } else {
            self::$url = self::url();
        }
        
        self::$url = filter_var(self::$url, FILTER_SANITIZE_URL);
    }

    public static function http404(): void
    {
        if (View::isAjax()) {
            self::throwJsonResponse(404, 'Page not found');
        }
        
        header("HTTP/1.0 404 Not Found");
        http_response_code(404);
        exit(require_once (view_path('errors/404.php')));
    }
    
    private function http405(): void
    {
        if (View::isAjax()) {
            self::throwJsonResponse(405, 'Method not allowed');
        }
        
        header("HTTP/1.0 405 Method Not Allowed");
        http_response_code(405);
        exit(require_once (view_path('errors/405.php')));
    }
    
    private static function throwJsonResponse(int $status, string $message)
    {
        Response::json(['msg' => $message], $status);
    }

    public static function run(): self
    {
        return new self();
    }
}
