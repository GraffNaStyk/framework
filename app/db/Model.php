<?php

namespace App\Db;

class Model
{
    public static $table;

    public static function __callStatic($name, $arguments)
    {
        $model = new Db(get_called_class());
        return $model->$name($arguments[0] ?? $arguments);
    }

    public static function table($table)
    {
        self::$table = $table;
        return new Db(get_called_class());
    }
}
