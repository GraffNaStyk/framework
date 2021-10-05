<?php

namespace App\Facades\Property;

use App\Facades\Validator\Type;

trait PropertyFacade
{
	private $params;
	
	public function setParams($params): void
	{
		$this->params = $params;
	}
	
	public function has(?string $offset): bool
	{
		return Has::check($this->params, $offset);
	}

	public function get(?string $offset=null)
	{
		if ($offset === null) {
			return $this->params;
		}

		return Get::check($this->params, $offset);
	}

	public function set($data, ?string $offset): void
	{
		$this->params = array_merge($this->params, Set::set($this->params, Type::get($data), $offset));
	}
	
	public function remove(?string $offset): void
	{
		$this->params = Remove::remove($this->params, $offset);
	}
}
