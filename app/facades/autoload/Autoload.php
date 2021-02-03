<?php

namespace App\Facades\Autoload;

class Autoload
{
    public static function run(string $class): void
    {
        $classArr = explode('\\', $class);
        $className = end($classArr);
    
        array_pop($classArr);
        $classArr = array_map('strtolower', $classArr);
        $path = '';
    
        foreach ($classArr as $namespaces) {
            $path .= $namespaces.'/';
        }
    
        $className = rtrim($className, '/');
    
        if ((bool) file_exists(path($path . $className .'.php')) === true) {
            require_once path($path . $className .'.php');
        }
    
        if ((bool) file_exists(path($path . $className .'.inc')) === true) {
            require_once path($path . $className .'.inc');
        }
    }
}
