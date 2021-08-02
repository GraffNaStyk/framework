<?php

namespace App\Facades\Db;

class TriggerResolver
{
    private static string $ns = '\\App\\Triggers\\';

    public static function resolve(string $object, string $method, Db $db)
    {
        $object = self::$ns.ucfirst($object).'Trigger';

        if (class_exists($object)) {
            (new $object($db))->{$method}();
        }
    }
}
