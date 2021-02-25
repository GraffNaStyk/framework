<?php

namespace App\Facades\Http\Router;

final class Collection
{
	private string $controller;
	private string $action;
	private string $namespace;
	private string $method;
	private int $rights;
	private array $middlewares = [];
	
	public function __construct
	(
		string $controller,
        string $action,
		string $namespace,
        string $method,
        int $rights,
        ?string $middleware
	) {
		$this->controller = $controller;
		$this->action = $action;
		$this->namespace = $namespace;
		$this->method = $method;
		$this->rights = $rights;
		
		if ($middleware) {
			$this->middlewares[] = $middleware;
		}
	}
	
	public function getController(): string
	{
		return $this->controller;
	}
	
	public function getAction(): string
	{
		return $this->action;
	}
	
	public function getNamespace(): string
	{
		return $this->namespace;
	}
	
	public function getMethod(): string
	{
		return $this->method;
	}
	
	public function getRights(): int
	{
		return $this->rights;
	}
	
	public function getMiddleware(): array
	{
		return $this->middlewares;
	}
	
	public function middleware(array $middlewares): void
	{
		$this->middlewares = [...$this->middlewares, ...$middlewares];
	}
}
