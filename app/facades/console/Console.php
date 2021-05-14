<?php

namespace App\Facades\Console;

class Console
{
    protected static array $argv = [];
    
    private static string $path = 'App\\Facades\\Console\\';
    
    private static array $backgrounds = [
        'black' => 40,
        'red' => 41,
        'green' => 42,
        'yellow' => 43,
        'blue' => 44,
        'magenta' => 45,
        'cyan' => 46,
        'light grey' => 47,
    ];
    
    public static function dispatch(array $argv)
    {
        array_shift ($argv);
        self::$argv = $argv;

        if (class_exists(self::$path.ucfirst(self::$argv[0]))) {
            $job = self::$path.ucfirst(self::$argv[0]);
            $tmp = self::$argv;
            array_shift ($tmp);
            array_shift ($tmp);
            $job = new $job($tmp);

            if (method_exists($job, self::$argv[1])) {
                $job->{self::$argv[1]}();
            } else {
                self::output('Method not exist: method -> '.self::$argv[1].' in class -> '.self::$argv[0], 'red');
                self::output('Remember: arg[0]=Class name in console directory, arg[1]=Method name in class, arg[2]=New file name', 'red');
            }
        } else {
            self::output('Class not exist: '.self::$path.ucfirst(self::$argv[0]), 'red');
            self::output('Remember: arg[0]=Class name in console directory, arg[1]=Method name in class, arg[2]=New file name', 'red');
        }
    }
    
    public function getArgs()
    {
        return self::$argv;
    }
    
    public static function end()
    {
        exit;
    }
    
    public static function output(string $output, $background='black')
    {
	    if (php_sapi_name() === 'cli') {
		    echo "\e[" . self::$backgrounds[mb_strtolower($background)] . "m" . $output . PHP_EOL . "\e[0m\n";
	    } else {
		    echo $output . '<br />';
		    flush();
		    ob_flush();
	    }
    }
}
