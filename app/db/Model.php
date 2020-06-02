<?php namespace App\Db;

class Model
{
    public static function __callStatic($name, $arguments)
    {
        $db = new Db(get_called_class());
        return $db->$name($arguments[0] ?? $arguments);
    }
}
