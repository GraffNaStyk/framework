<?php

namespace App\Facades\Http;

use App\Facades\Property\Get;
use App\Facades\Property\Has;
use App\Facades\Property\Remove;
use App\Facades\Property\Set;
use App\Helpers\Session;

final class Request
{
    protected array $file = [];
    
    private string $method = 'post';
    
    protected array $data = [];
    
    protected array $headers = [];
    
    public function __construct()
    {
        $this->boot();
    }
    
    public function boot(): void
    {
        $this->setMethod();
        $this->setHeaders();
    }

    private function setMethod()
    {
        if (isset($_FILES) && !empty($_FILES)) {
            $this->file = $_FILES;
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $this->method = 'post';
                $this->data = $_POST;
                break;
            case 'GET':
                $this->method = 'get';
                $this->data = $_GET;
                break;
	        case 'DELETE':
		        $this->method = 'delete';
		        $this->data = (array) json_decode(file_get_contents('php://input'));
		        break;
	        case 'PUT':
		        $this->method = 'put';
		        $this->data = (array) json_decode(file_get_contents('php://input'));
		        break;
        }
    }
    
    private function setHeaders(): void
    {
        foreach (apache_request_headers() as $key => $item) {
            $this->headers[mb_strtolower($key)] = $item;
        }
    }
    
    public function header(string $header)
    {
        return isset($this->headers[mb_strtolower($header)]) ? $this->headers[mb_strtolower($header)] : false;
    }
    
    public function headers(): array
    {
        return $this->headers;
    }
	
	public function setData(array $data)
	{
		$this->data = array_merge($this->data, $data);
	}
    
    public function getData(): array
    {
        return $this->data;
    }

    public function sanitize()
    {
        foreach ($this->data as $key => $item) {
            if (is_array($item)) {
                $this->data[$key] = $this->reSanitize($item);
            } else {
                $this->data[$key] = $this->clear($item);
            }
        }
    }
    
    public function reSanitize(array $data): array
    {
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                $this->reSanitize($item);
            } else {
                $data[$key] = $this->clear($item);
            }
        }
        
        return $data;
    }
    
    private function clear($item)
    {
        if ((string) $item !== '') {
            $item = trim($item);
        }
    
        if (! is_numeric($item)) {
            $item = urldecode($item);
        }
        
        $item = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $item);
        $item = preg_replace('/<a(.*?)>(.+)<\/a>/', '', $item);
        $item = strtr($item,
            "???????��������������������������������������������������������������",
            "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy"
        );

        $item = preg_replace('/(;|\||`|&|^|{|}|[|]|\)|\()/i', '', $item);
        $item = preg_replace('/(\)|\(|\||&)/', '', $item);
        
        return $item;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function get($item = null)
    {
        if ($item == null) {
            return $this->data;
        }

        return Get::check($this->data, $item);
    }
    
    public function input($item = null)
    {
        return $this->get($item);
    }

    public function all(): array
    {
        return $this->data;
    }

    public function file($file = null)
    {
        if ($file !== null && isset($this->file[$file])) {
            return $this->file[$file];
        }
        
        return $this->file;
    }

    public function has($item)
    {
        return Has::check($this->data, $item);
    }

    public function set($item, $data): void
    {
	    $this->data = array_merge($this->data, Set::set($this->data, $data, $item));
    }

    public function remove($item): void
    {
        $this->data = Remove::remove($this->data, $item);
    }
    
    public static function isAjax(): bool
    {
        if (isset($_SERVER['HTTP_X_FETCH_HEADER'])
            && (string) strtolower($_SERVER['HTTP_X_FETCH_HEADER']) === 'fetchapi'
        ) {
            return true;
        }
        
        return false;
    }

    public function __destruct()
    {
        Session::checkIfDataHasBeenProvided($this->data);
    }
}
