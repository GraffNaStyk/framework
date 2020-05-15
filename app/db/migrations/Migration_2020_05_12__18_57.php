<?php namespace App\Db\Migrations;

class Migration_2020_05_12__18_57 extends Migration
{
    public function up()
    {
        if($this->notExist('users', 'test')) {
            $this->query('ALTER TABLE users ADD COLUMN `test` varchar (90)');
        }
    }
}
