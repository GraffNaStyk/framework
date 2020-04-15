<?php namespace App\Core;

use App\Facades\Csrf\Csrf;
use App\Facades\Dotter\Get;
use App\Facades\Dotter\Has;
use App\Helpers\Session;

class Request
{
    protected $post = [];
    protected $get = [];
    protected $file = [];
    private $method = 'post';

    public function __construct()
    {
        $this->setMethod();

        if(!empty($this->post) && app['csrf'] && ! View::isAjax()) {
            if(! isset($this->post['csrf']) || ! Csrf::isValid($this->post['csrf'])) {
                Session::msg('Wrong token', 'danger');
                Router::redirect('Dash/http404');
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
            case $_SERVER['REQUEST_METHOD'] == 'POST':
                $this->method = 'post';
                $this->post = $_POST;
                break;
            case $_SERVER['REQUEST_METHOD'] == 'GET':
                $this->method = 'get';
                $this->get = $_GET;
                break;
        }

        $this->sanitize();
    }

    private function sanitize()
    {
        foreach ($this->{$this->method} as $key => $item)
            $this->{$this->method}[$key] = $item ?? trim($item);
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
        $method = $this->method;
        $item = explode('.', $item);
        if (isset($item[3])) {
            $this->$method[$item[0]][$item[1]][$item[2]][$item[3]] = $data;
        } else if (isset($item[2])) {
            $this->$method[$item[0]][$item[1]][$item[2]] = $data;
        } else if (isset($item[1])) {
            $this->$method[$item[0]][$item[1]] = $data;
        } else {
            $this->$method[$item[0]] = $data;
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
