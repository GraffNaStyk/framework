<?php

namespace App\Db;

class Model
{

    public static function __callStatic(string $name, array $arguments)
    {
	    if (isset($arguments[1])) {
		    return call_user_func_array([new Db(get_called_class()), $name], $arguments);
	    }
	
	    return (new Db(get_called_class()))->$name($arguments[0] ?? $arguments);
    }
}