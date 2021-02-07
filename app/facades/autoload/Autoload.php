<?php

namespace App\Facades\Autoload;

class Autoload
{
    public static function run(string $class): void
    {
        $class = mb_strtolower(str_replace('\\', '/', $class)).'.php';
        
        if ((bool) file_exists(path($class))) {
            require_once path($class);
        }
    }
}
