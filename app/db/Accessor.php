<?php

namespace App\Db;

class Accessor
{
    public static function __callStatic($name, $arguments)
    {
        $model = new Db(get_called_class());
        return $model->$name($arguments[0] ?? $arguments);
    }
}
