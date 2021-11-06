<?php

namespace App\Facades\Http;

use App\Facades\Header\Header;

class Response
{
	use Header;
	
	const RESPONSE_CODES = [
		400 => 'Bad Request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		500 => 'Internal Server Error'
	];
	
	private array $data = [];
	
	private ?string $content = null;
	
	private bool $isJsonResponse = false;
	
	private bool $isXmlResponse = false;
	
	private array $customHeaders = [];
	
	private int $responseCode = 200;
	
	public function __construct()
	{
		return $this;
	}
	
	public function setHeader(string $name, string $value): self
	{
		$this->customHeaders[$name] = $value;
		
		return $this;
	}
	
	public function setHeaders(array $headers): self
	{
		foreach ($headers as $key => $header) {
			$this->setHeader($key, $header);
		}
		
		return $this;
	}
	
	public function json(): self
	{
		$this->isJsonResponse = true;
		
		return $this;
	}
	
	public function xml(): self
	{
		$this->isXmlResponse = true;
		$this->setHeader('Content-type', 'text/xml;charset=utf-8');
		
		return $this;
	}
	
	public function setData(array $data): self
	{
		$this->data	= $data;
		
		return $this;
	}
	
	public function setContent(string $content): self
	{
		$this->content = $content;
		
		return $this;
	}
	
	public function send(): self
	{
		return $this;
	}
	
	public function setCode(int $code = 200): self
	{
		$this->responseCode = $code;
		
		return $this;
	}
	
	public function getResponse(): string
	{
		$this->prepareHeaders();
		http_response_code($this->responseCode);
		
		if (! empty($this->customHeaders)) {
			foreach ($this->customHeaders as $name => $value) {
				\header($name.': '.$value);
			}
		}
		
		if ($this->content !== null || $this->isXmlResponse) {
			return $this->content;
		} else if ($this->isJsonResponse) {
			return json_encode($this->data);
		}

		throw new \LogicException('Wrong response send! Set response content or response data');
	}
}
