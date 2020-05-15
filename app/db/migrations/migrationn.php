<?php namespace App\Db\Migrations;

if(isset($argv[1]) && (string) $argv[1] === 'make') {
    file_put_contents(__DIR__.'/migration_'.date('Y_m_d__H_i').'.php',
"<?php namespace App\Db\Migrations;

class migration_".date('Y_m_d__H_i') . " extends Migration
{
    public function up()
    {
    }
}
");
}

if(isset($argv[1]) && (string) $argv[1] === 'run') {
    foreach (glob('migration_*.php') as $migration) {
        $migration = new $migration();
        $migration->up();
    }
}
