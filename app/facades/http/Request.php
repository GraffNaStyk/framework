<?php namespace App\Facades\Http;

use App\Facades\Csrf\Csrf;
use App\Facades\Dotter\Get;
use App\Facades\Dotter\Has;
use App\Helpers\Session;

final class Request
{
    protected array $post    = [];
    protected array $get     = [];
    protected array $delete  = [];
    protected array $put     = [];
    protected array $file    = [];
    private string $method   = 'post';

    public function __construct()
    {
        $this->setMethod();

        if(!empty($this->post) && app['csrf'] && ! View::isAjax()) {
            if(! isset($this->post['csrf']) || ! Csrf::isValid($this->post['csrf'])) {
                Session::msg('Wrong token', 'danger');
                Router::http404();
            }
        }

        Session::remove('csrf');
        unset($this->post['csrf']);

    }

    private function setMethod()
    {
        if (isset($_FILES) && !empty($_FILES)) {
            $this->file = $_FILES;
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case (string) $_SERVER['REQUEST_METHOD'] === 'POST':
                $this->method = 'post';
                $this->post = $_POST;
                break;
            case (string) $_SERVER['REQUEST_METHOD'] === 'GET':
                $this->method = 'get';
                $this->get = $_GET;
                break;
            case (string) $_SERVER['REQUEST_METHOD'] === 'DELETE':
                $this->method = 'delete';
                $this->delete = json_decode(file_get_contents('php://input'));
                break;
            case (string) $_SERVER['REQUEST_METHOD'] === 'PUT':
                $this->method = 'put';
                $this->put = json_decode(file_get_contents('php://input'));
                break;
        }

        $this->sanitize();
    }
    
    private function sanitize()
    {
        foreach ($this->{$this->method} as $key => $item) {
            $tmp = !is_array($item) ? trim($item) : $item;
            $tmp = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $tmp);
            $this->{$this->method}[$key] = $tmp;
        }
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function get($item = null)
    {
        if($item == null)
            return $this->{$this->method};

        return Get::check($this->{$this->method}, explode('.', $item));
    }

    public function all()
    {
        return $this->{$this->method};
    }

    public function file($file = null)
    {
        if(!is_null($file) && isset($this->file[$file]))
            return $this->file[$file];

        return $this->file;
    }

    public function has($item)
    {
        return Has::check($this->{$this->method}, explode('.', $item));
    }

    public function set($item, $data)
    {
        $item = explode('.', $item);
        if (isset($item[3])) {
            $this->{$this->method}[$item[0]][$item[1]][$item[2]][$item[3]] = $data;
        } else if (isset($item[2])) {
            $this->{$this->method}[$item[0]][$item[1]][$item[2]] = $data;
        } else if (isset($item[1])) {
            $this->{$this->method}[$item[0]][$item[1]] = $data;
        } else {
            $this->{$this->method}[$item[0]] = $data;
        }
    }

    public function remove($item)
    {
        $item = explode('.', $item);
        if (isset($item[1])) {
            unset($this->{$this->method}[$item[0]][$item[1]]);
        } else {
            unset($this->{$this->method}[$item[0]]);
        }
    }

    public function debug()
    {
        pd(['post' => $_POST, 'get' => $_GET]);
    }

    public function __destruct()
    {
        Session::checkIfDataHasBeenProvided($this->{$this->method});
    }
}