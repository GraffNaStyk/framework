<?php

namespace App\Facades\Env;

use App\Facades\Property\Get;

class Env
{
	private static array $env;
	
    public static function set(): void
    {
    	if (! empty(static::$env)) {
    		return;
	    }
    	
        $env = file_get_contents(app_path('app/config/.env'));
        $environment = [];

        foreach (array_filter(explode(PHP_EOL, $env)) as $item) {
            $item = explode('=', $item);
            $environment[trim($item[0])] = trim($item[1]);
        }
	
	    static::$env = $environment;
    }
    
    public static function get(string $item = null)
    {
    	if ($item) {
		    return Get::check(static::$env, $item);
	    }
    	
    	return static::$env;
    }
}
