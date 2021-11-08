<?php

namespace App\Facades\Http;

use App\Facades\Config\Config;
use App\Facades\Header\Header;
use App\Facades\Property\Get;
use App\Facades\Property\Has;
use App\Facades\Property\Remove;
use App\Facades\Property\Set;
use App\Facades\Validator\Type;

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
        $this->isOptionsCall();
        $this->setHeaders();
        $this->setMethod();
    }

    public function isOptionsCall(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            Header::setAllowedOptions();
            return true;
        }

        return false;
    }

    private function setMethod()
    {
        if (isset($_FILES) && ! empty($_FILES)) {
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

        if (empty($this->data) && defined('API') && API === true) {
            $this->data = (array) json_decode(file_get_contents('php://input'));
        }
    }

    public function isPost(): bool
    {
        return $this->method === 'post';
    }

    public function isGet(): bool
    {
        return $this->method === 'get';
    }

    public function isDelete(): bool
    {
        return $this->method === 'delete';
    }

    public function isPut(): bool
    {
        return $this->method === 'put';
    }
	
	private function setHeaders(): void
	{
		if (function_exists('getallheaders')) {
			foreach (getallheaders() as $key => $item) {
				$this->headers[mb_strtolower($key)] = $item;
			}
		}
	}

    public function header(string $header)
    {
        return isset($this->headers[mb_strtolower($header)]) ? $this->headers[mb_strtolower($header)] : false;
    }

    public function hasHeader(string $item): bool
    {
        return Has::check($this->headers(), $item);
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function setData(array $data): void
    {
        $this->data = array_merge($this->data, $data);
    }

    public function getData(): array
    {
        return $this->data;
    }
	
	public function sanitize(): void
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
	            $data[$key] = $this->reSanitize($item);
            } else {
                $data[$key] = $this->clear($item);
            }
        }

        return $data;
    }

    private function clear($item)
    {
        if (! is_numeric($item)) {
            $item = (string) urldecode($item);
        }
	
	    if (Config::get('app.security.enabled')) {
		    $item = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $item);
		    $item = preg_replace('/<noscript\b[^>]*>(.*?)<\/noscript>/is', '', $item);
		    $item = preg_replace('/<a(.*?)>(.+)<\/a>/', '', $item);
		    $item = preg_replace('/<iframe(.*?)>(.+)<\/iframe>/', '', $item);
		    $item = preg_replace('/<img (.*?)>/is', '', $item);
		    $item = preg_replace('/<embed (.*?)>/is', '', $item);
		    $item = preg_replace('/<link (.*?)>/is', '', $item);
		    $item = preg_replace('/<video (.*?)>(.+)<\/video>/', '', $item);
	    }
	
	    $item = filter_var($item, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH);
        $item = strtr(
            $item,
            '???????��������������������������������������������������������������',
            'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy'
        );

        $item = preg_replace('/(;|\||`|&|^|{|}|[|]|\)|\()/i', '', $item);
        $item = preg_replace('/(\)|\(|\||&)/', '', $item);
	    $item = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $item);
	    
        return Type::get($item);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function get(string $offset = null)
    {
        if ($offset === null) {
            return $this->data;
        }

        return Get::check($this->data, $offset);
    }

    public function input(string $item = null)
    {
        return $this->get($item);
    }

    public function all(): array
    {
        return (array) $this->data;
    }

    public function file(?string $file = null)
    {
        if ($file !== null && isset($this->file[$file])) {
            return $this->file[$file];
        }

        return $this->file;
    }

    public function has(string $offset): bool
    {
        return Has::check($this->data, $offset);
    }

    public function set($item, $data): void
    {
        $this->data = array_merge($this->data, Set::set($this->data, Type::get($data), $item));
    }

    public function remove(string $offset): void
    {
        $this->data = Remove::remove($this->data, $offset);
    }
	
	public static function isAjax(): bool
	{
		$headers = getallheaders();
		
		if (isset($headers['Is-Fetch-Request'])
			&& (string) mb_strtolower($headers['Is-Fetch-Request']) === 'true'
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
