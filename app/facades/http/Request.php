<?php

namespace App\Facades\Http;

use App\Facades\Header\Header;
use App\Facades\Property\Get;
use App\Facades\Property\Has;
use App\Facades\Property\PropertyFacade;
use App\Facades\Property\Remove;
use App\Facades\Property\Set;
use App\Facades\Security\Sanitizer;
use App\Facades\Validator\Type;

final class Request
{
	use PropertyFacade;
	
    protected array $file = [];

    private string $method = 'post';

    protected array $data = [];

    protected array $headers = [];
    
    private Sanitizer $sanitizer;

    const METHOD_POST = 'post';
    const METHOD_GET = 'get';
    const METHOD_PUT = 'put';
    const METHOD_DELETE = 'delete';
    const METHOD_OPTIONS = 'OPTIONS';
    
    public function __construct()
    {
        $this->boot();
    }

    private function boot(): void
    {
    	$this->sanitizer = new Sanitizer();
        $this->isOptionsCall();
        $this->setHeaders();
        $this->setMethod();
    }

    public function isOptionsCall(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === self::METHOD_OPTIONS) {
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

        if ($this->hasHeader('Content-Type')
	        && mb_strtolower($this->header('Content-Type')) === 'application/json'
        ) {
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
        return Has::check($this->headers(), mb_strtolower($item));
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
				$this->data[$key] = $this->sanitizer->clear($item);
			}
		}
	}

    public function reSanitize(array $data): array
    {
        foreach ($data as $key => $item) {
            if (is_array($item)) {
	            $data[$key] = $this->reSanitize($item);
            } else {
                $data[$key] = $this->sanitizer->clear($item);
            }
        }

        return $data;
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
		if (php_sapi_name() === 'cli') {
			return false;
		}
		
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
