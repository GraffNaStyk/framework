<?php

namespace App\Facades\Console;

class Console
{
    protected static array $argv = [];
    
    private static string $path = 'App\\Facades\\Console\\';
    
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
                echo 'Method not exist: method -> '.self::$argv[1].' in class -> '.$job.PHP_EOL;
                echo 'Remember: arg[0]=Class name in console directory, arg[1]=Method name in class, arg[2]=New file name'.PHP_EOL;
            }
        } else {
            echo 'Class not exist: '.self::$path.ucfirst(self::$argv[0]).PHP_EOL;
            echo 'Remember: arg[0]=Class name in console directory, arg[1]=Method name in class, arg[2]=New file name'.PHP_EOL;
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
}
