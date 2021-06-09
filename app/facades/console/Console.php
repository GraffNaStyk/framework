<?php

namespace App\Facades\Console;

class Console
{
    protected static array $argv = [];
    
    private static string $path = 'App\\Facades\\Console\\';
    
    private static array $backgrounds = [
        'black'      => 40,
        'red'        => 41,
        'green'      => 42,
        'yellow'     => 43,
        'blue'       => 44,
        'magenta'    => 45,
        'cyan'       => 46,
        'light grey' => 47,
    ];
    
    public static function dispatch(array $argv)
    {
        array_shift ($argv);
        self::$argv = $argv;

        if (class_exists(self::$path.ucfirst(self::$argv[0]))) {
            $job = self::$path.ucfirst(self::$argv[0]);
	        array_shift(self::$argv);
            new $job(self::$argv);
        } else {
            self::output('Class not exist: '.self::$path.ucfirst(self::$argv[0]), 'red');
        }
    }
    
    public static function end()
    {
        exit;
    }
    
    public static function output($output, $background='black')
    {
	    if (php_sapi_name() === 'cli') {
		    echo "\e[" . self::$backgrounds[mb_strtolower($background)] . "m" . $output . "\e[0m\n";
	    } else {
	    	if (is_array($output) || is_object($output)) {
	    		pd($output, false);
		    } else {
			    echo date('Y-m-d H:i:s').' '.$output . '<br />';
		    }

		    flush();
		    ob_flush();
	    }
    }
}
