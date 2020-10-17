<?php

namespace App\Facades\Dispatcher;

class Dispatcher
{
    protected static array $argv = [];
    
    protected static array $canDo = [];
    
    public static function dispatch(&$argv)
    {
        if ((string) php_sapi_name() !== 'cli') {
            header('location: index.php');
        }
        
        self::$argv = $argv;
        return new self();
    }
    
    public function register(array $can):void
    {
        self::$canDo = $can;
    }
    
    public function do (string $what): bool
    {
        if (in_array($what, self::$canDo) === true
            && (string) $what === self::$argv[1]
        ) {
            return true;
        }
        
        return false;
    }
    
    public function getArgs()
    {
        return self::$argv;
    }
    
    public function end()
    {
        exit;
    }
}
