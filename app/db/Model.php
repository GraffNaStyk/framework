<?php

namespace App\Db;

class Model
{
    private static ?object $db = null;

    public static function __callStatic($name, $arguments)
    {
	    self::$db = new Db(get_called_class());
	
	    if (isset($arguments[1])) {
		    return call_user_func_array([self::$db, $name], $arguments);
	    }
	
	    return self::$db->$name($arguments[0] ?? $arguments);
    }
}
