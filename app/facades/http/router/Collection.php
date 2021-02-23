<?php

namespace App\Facades\Http\Router;

final class Collection
{
	private string $controller;
	private string $action;
	private string $namespace;
	private string $method;
	private int $rights;
	private ?string $middleware = null;
	
	public function controller(string $controller): void
	{
		$this->controller = $controller;
	}
	
	public function action(string $action): void
	{
		$this->action = $action;
	}
	
	public function namespace(string $namespace): void
	{
		$this->namespace = $namespace;
	}
	
	public function method(string $method): void
	{
		$this->method = $method;
	}
	
	public function rights(int $rights): void
	{
		$this->rights = $rights;
	}
	
	public function middleware(?string $middleware): void
	{
		$this->middleware = $middleware;
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
	
	public function getMiddleware(): ?string
	{
		return $this->middleware;
	}
}
