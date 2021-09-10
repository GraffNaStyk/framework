<?php

namespace App\Facades\Property;

use App\Facades\Validator\Type;

trait PropertyFacade
{
	public function has($iterable, $offset): bool
	{
		return Has::check($iterable, $offset);
	}
	
	public function get($iterable, $offset)
	{
		return Get::check($iterable, $offset);
	}
	
	public function set($item, $data): array
	{
		return array_merge($data, Set::set($data, Type::get($data), $item));
	}
	
	public function remove($iterable, $offset): array
	{
		return Remove::remove($iterable, $offset);
	}
}
