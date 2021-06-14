<?php

namespace App\Facades\Db;

class ObserverResolver
{
    private static string $ns = '\\App\\Observers\\';

    public static function resolve(string $object, string $method)
    {
        $object = self::$ns.ucfirst($object).'Observer';

        if (class_exists($object)) {
            (new $object)->{$method}();
        }
    }
}
