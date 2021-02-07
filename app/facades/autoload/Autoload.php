<?php

namespace App\Facades\Autoload;

class Autoload
{
    public static function run(string $class): void
    {
        $classArr = explode('\\', $class);
        $className = end($classArr);
        array_pop($classArr);
        $className = mb_strtolower(implode('/', $classArr)).'/'.$className.'.php';
        
        if ((bool) is_readable(path($className))) {
            require_once path($className);
        }
    }
}
