<?php namespace App\Db;

class Model
{
    private static ?object $db = null;

    public static function __callStatic($name, $arguments)
    {
        if (self::$db instanceof Db) {
            self::$db->setTable(get_called_class());
        } else {
            self::$db = new Db(get_called_class());
        }
        return self::$db->$name($arguments[0] ?? $arguments);
    }
}
