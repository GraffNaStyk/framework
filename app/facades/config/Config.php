<?php

namespace App\Facades\Config;

use App\Facades\Property\Get;
use App\Facades\Property\Has;
use App\Facades\Property\Set;
use App\Facades\Validator\Type;

class Config
{
	private static array $memory = [];
	
	public static function set(string $key, $value)
	{
		static::$memory = array_merge(static::$memory, Set::set(static::$memory, Type::get($value), $key));
	}
	
	public static function get(string $key)
	{
		return Get::check(static::$memory, $key);
	}
	
	public static function has(string $key)
	{
		return Has::check(self::$memory, $key);
	}
	
	public static function init()
	{
		foreach (scandir(app_path('app/config')) as $item) {
			if (pathinfo($item, PATHINFO_EXTENSION) === 'php' && strpos($item, 'example') === false) {
				$val = require_once app_path('app/config/' . $item);
				static::set(pathinfo($item, PATHINFO_FILENAME), $val);
			}
		}
	}
}
