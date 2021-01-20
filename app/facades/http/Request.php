<?php
namespace App\Facades\Http;

use App\Facades\Csrf\Csrf;
use App\Facades\Dotter\Get;
use App\Facades\Dotter\Has;
use App\Helpers\Session;

final class Request
{
    protected array $file = [];
    private string $method = 'post';
    protected array $data = [];
    protected array $headers = [];
    
    public function __construct()
    {
        $this->setMethod();
        $this->setHeaders();
        
        if(!empty($this->data) && app('csrf') && ! View::isAjax()) {
            if(! isset($this->data['csrf']) || ! Csrf::isValid($this->data['csrf'])) {
                Router::http404();
            }
        }

        Session::remove('csrf');
        unset($this->data['csrf']);

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
                $this->data = json_decode(file_get_contents('php://input'));
                break;
            case 'PUT':
                $this->method = 'put';
                $this->data = json_decode(file_get_contents('php://input'));
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
        $this->data = $data;
    }
    
    public function getData()
    {
        return $this->data;
    }

    public function sanitize()
    {
        foreach ($this->data as $key => $item) {
            if (is_array($item) === true) {
                $this->data[$key] = $this->reSanitize($item);
            } else {
                $this->data[$key] = $this->clear($item);
            }
        }
    }
    
    public function reSanitize(array $data)
    {
        foreach ($data as $key => $item) {
            if (is_array($item) === true) {
                $this->reSanitize($item);
            } else {
                $data[$key] = $this->clear($item);
            }
        }
        return $data;
    }
    
    private function clear($item)
    {
        if ($item !== null && $item !== '') {
            $item = trim($item);
        }
    
        if (!is_numeric($item)) {
            $item = urldecode($item);
        }
        
        $item = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $item);
        $item = strtr($item,
            "???????��������������������������������������������������������������",
            "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy"
        );
        $item = preg_replace('/(;|\||`|&|^|{|}|[|]|\)|\()/i', '', $item);
        $item = preg_replace('/(\)|\(|\||&)/', '', $item);
        return $item;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function get($item = null)
    {
        if($item == null)
            return $this->data;

        return Get::check($this->data, explode('.', $item));
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
        if(!is_null($file) && isset($this->file[$file]))
            return $this->file[$file];

        return $this->file;
    }

    public function has($item)
    {
        return Has::check($this->data, explode('.', $item));
    }

    public function set($item, $data)
    {
        $item = explode('.', $item);
        if (isset($item[3])) {
            $this->data[$item[0]][$item[1]][$item[2]][$item[3]] = $data;
        } else if (isset($item[2])) {
            $this->data[$item[0]][$item[1]][$item[2]] = $data;
        } else if (isset($item[1])) {
            $this->data[$item[0]][$item[1]] = $data;
        } else {
            $this->data[$item[0]] = $data;
        }
    }

    public function remove($item)
    {
        $item = explode('.', $item);
        if (isset($item[1])) {
            unset($this->data[$item[0]][$item[1]]);
        } else {
            unset($this->data[$item[0]]);
        }
    }

    public function debug()
    {
        pd(['post' => $_POST, 'get' => $_GET]);
    }

    public function __destruct()
    {
        Session::checkIfDataHasBeenProvided($this->data);
    }
}
