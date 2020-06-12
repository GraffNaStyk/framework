<?php

if (php_sapi_name() !== 'cli') {
    header('location: index.php');
}

if(isset($argv[1]) && (string) $argv[1] === 'make') {
    $migration = file_get_contents(__DIR__.'/app/facades/migrations/migration');
    $migration = str_replace('CLASSNAME', 'Migration_'.date('Y_m_d__H_i'), $migration);
    $migration = str_replace('MODEL', $argv[2], $migration);
    
    if(!is_dir(__DIR__.'/app/db/migrate/'))
        mkdir(__DIR__.'/app/db/migrate/', 0775, true);
    
    file_put_contents(__DIR__.'/app/db/migrate/Migration_'.date('Y_m_d__H_i').'.php', "<?php ".$migration);
    
    if(file_exists(__DIR__.'/app/model/'.ucfirst($argv[2]).'.php') === false) {
        $model = file_get_contents(__DIR__.'/app/facades/migrations/model');
        $model = str_replace('CLASSNAME', ucfirst($argv[2]), $model);
        $model = str_replace('TABLE', $argv[3], $model);
        file_put_contents(__DIR__.'/app/model/'.ucfirst($argv[2]).'.php', "<?php ".$model);
    }
}

if(isset($argv[1]) && ((string) $argv[1] === 'up' || (string) $argv[1] === 'down')) {
    require_once __DIR__.'/index.php';
    foreach (glob(__DIR__.'/app/db/migrate/Migration_*.php') as $migration) {
        $migration = 'App\\Db\\Migrate\\'.basename(str_replace('.php','', $migration));
        $migration = new $migration();
        if($argv[1] === 'up') {
            $migration->up(new \App\Facades\Migrations\Schema('App\\Model\\'.$migration->model));
        } else {
            $migration->down(new \App\Facades\Migrations\Schema('App\\Model\\'.$migration->model));
        }
    }
}
