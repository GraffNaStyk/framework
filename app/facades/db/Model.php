<?php

namespace App\Facades\Db;

class Model
{
	public static function __callStatic(string $name, array $arguments)
	{
		return self::resolveMagicCall($name, $arguments);
	}
	
	public function __call(string $name, array $arguments)
	{
		return self::resolveMagicCall($name, $arguments);
	}
	
	private static function resolveMagicCall(string $name, array $arguments)
	{
		$db = new Db(get_called_class());
		$db->connect();

		if (isset($arguments[1])) {
			return call_user_func_array([$db, $name], $arguments);
		} else {
			return $db->$name($arguments[0] ?? $arguments);
		}
	}
}
