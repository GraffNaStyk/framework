<?php namespace App\Db\Migrations;

use App\Db\Db;

abstract class Migration
{
    public function notExist(string $table, $field)
    {
        return Db::raw("SHOW COLUMNS FROM `$table` LIKE $field");
    }

    public function query(string $query)
    {
        return Db::raw($query);
    }
}
