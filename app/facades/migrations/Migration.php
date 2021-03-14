<?php

namespace App\Facades\Migrations;

use App\Facades\Console\Console;
use App\Facades\Console\Model;
use App\Helpers\Storage;

class Migration
{
    public function make($args)
    {
        $migration = file_get_contents(app_path('app/facades/migrations/migration'));
        $migration = str_replace('CLASSNAME', 'Migration_'.$args[0].'_'.date('Y_m_d__H_i'), $migration);
        $migration = str_replace('MODEL', $args[0], $migration);
        
        if (is_dir(app_path('app/db/migrate/')) === false) {
            mkdir(app_path('app/db/migrate/'), 0775, true);
        }
        
        if (file_put_contents(
            app_path('app/db/migrate/Migration_'.$args[0].'_'.date('Y_m_d__H_i').'.php'),
            $migration
        )) {
            Console::output(
                'Migration app/db/migrate/Migration_'.$args[0].'_'.date('Y_m_d__H_i').' has been created',
                'blue'
            );
        }
        
        if (file_exists(app_path('app/model/'.ucfirst($args[0]).'.php')) === false) {
            (new Model([$args[0], $args[1]]))->make();
        }
    }
    
    public function up(bool $isDump = false)
    {
        $this->makeJsonFile();
        $migrationContent = (array) json_decode(
            Storage::private()->get('db/migrations.json'),
            true
        );

        foreach ($this->sortByDate(glob(app_path('app/db/migrate/Migration_*.php'))) as $migration) {
            $migration = 'App\\Db\\Migrate\\'.basename(str_replace('.php','', $migration));
            
            if (!isset($migrationContent[$migration]) || $isDump) {
                $migrationContent[$migration] = ['date' => date('Y-m-d H:i:s')];
                $migration = new $migration();
                Console::output('Migration '.get_class($migration). ' has been make', 'blue');
                $migration->up(new Schema(app['model-provider'].$migration->model, $isDump));
            }
        }
        
        Storage::private()
            ->put('db/migrations.json', json_encode($migrationContent, JSON_PRETTY_PRINT), true);
    }
    
    public function down()
    {
        $this->makeJsonFile(true);
        
        foreach (glob(app_path('app/db/migrate/Migration_*.php')) as $migration) {
            $migration = 'App\\Db\\Migrate\\'.basename(str_replace('.php','', $migration));
            $migration = new $migration();
            $migration->down(new Schema(app['model-provider'].$migration->model));
        }
        
        Storage::private()->remove('db/migrations.json');
    }
    
    public function dump()
    {
        $this->up(true);
    }
    
    private function makeJsonFile($replace = false)
    {
        Storage::private()->make('db');
        Storage::private()->put('db/migrations.json', '{}', $replace);
    }
    
    private function sortByDate(array $files): array
    {
        $migrations = [];
        
        foreach ($files as $key => $file) {
            $tmp = str_replace(app_path('app/db/migrate/Migration_'), '', $file);
            $tmp = preg_replace('/[a-zA-Z__.]/', '', $tmp);
            $migrations[$tmp] = $file;
        }
        
        ksort($migrations);
        return $migrations;
    }
}
