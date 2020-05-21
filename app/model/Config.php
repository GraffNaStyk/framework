<?php namespace App\Model;

use App\Db\Model;

class Config extends Model
{
    public static $table = 'config';

    public static function getHeaderColor()
    {
        Db::raw("SELECT U.COS, U.KTOS FROM {self::$table} ");
    }
}
