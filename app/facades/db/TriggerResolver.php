<?php

namespace App\Facades\Db;

use App\Facades\Config\Config;

class TriggerResolver
{
    public static function resolve(string $object, string $method, Db $db)
    {
        $object = Config::get('app.triggers_path').ucfirst($object).'Trigger';

        if (class_exists($object)) {
            (new $object($db))->{$method}();
        }
    }
}
