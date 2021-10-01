<?php

namespace App\Facades\Db;

class Model
{
	public static function __callStatic(string $name, array $arguments): Db
	{
		return self::resolveMagicCall($name, $arguments);
	}
	
	public function __call(string $name, array $arguments): Db
	{
		return self::resolveMagicCall($name, $arguments);
	}
	
	private static function resolveMagicCall(string $name, array $arguments): Db
	{
		if (isset($arguments[1])) {
			$db = call_user_func_array([new Db(get_called_class()), $name], $arguments);
		} else {
			$db = new Db(get_called_class());
			$db->$name($arguments[0] ?? $arguments);
		}
		
		$db->connect();
		return $db;
	}
}
