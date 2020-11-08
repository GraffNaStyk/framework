<?php

namespace App\Facades\Console;

class Console
{
    protected static array $argv = [];
    
    protected static array $canDo = [];
    
    private static string $path = 'App\\Facades\\Console\\';
    
    public static function dispatch(array $argv)
    {
        array_shift ($argv);
        self::$argv = $argv;

        if (class_exists(self::$path.ucfirst(self::$argv[0]))) {
            $job = self::$path.ucfirst(self::$argv[0]);
            $job = new $job();

            if (method_exists($job, self::$argv[1])) {
                $job->{self::$argv[1]}();
            }
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
