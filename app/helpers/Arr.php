<?php


namespace App\Helpers;


use App\Facades\Property\Get;
use App\Facades\Property\Has;
use App\Facades\Property\Remove;
use App\Facades\Property\Set;
use App\Facades\Validator\Type;

class Arr
{
	public static function each(array $arr, callable $function): array
	{
		foreach ($arr as $key => $item) {
			$res = $function($key, $item);
			
			if ($res !== null) {
				$arr[$key] = $function($key, $item);
			} else {
				unset($arr[$key]);
			}
		}
		
		return $arr;
	}

	public static function has(array $arr, string $offset): bool
	{
		return Has::check($arr, $offset);
	}
	
	public static function get(array $arr, string $offset)
	{
		return Get::check($arr, $offset);
	}
	
	public static function set(array $arr, $item, $data): array
	{
		return array_merge($arr, Set::set($arr, Type::get($data), $item));
	}
	
	public static function remove(array $arr, $item): array
	{
		return Remove::remove($arr, $item);
	}
}
