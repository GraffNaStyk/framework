<?php

namespace App\Facades\Dependency;

class Container
{
	private array $items = [];
	
	public function add(string $name, object $object): void
	{
		if (! $this->has($name)) {
			$this->items[$name] = $object;
		}
	}
	
	public function has(string $name): bool
	{
		return isset($this->items[$name]);
	}
	
	public function get(string $name)
	{
		return $this->items[$name];
	}
}
