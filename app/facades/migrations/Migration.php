<?php

namespace App\Facades\Migrations;

use App\Facades\Config\Config;
use App\Facades\Console\Model;
use App\Facades\Db\Db;
use App\Facades\Storage\Storage;

class Migration
{
	const MIGRATION_DIR = '\\App\\Migrate\\';
	
    public function up(bool $isDump = false)
    {
        $this->makeJsonFile();

        $migrationContent = (array) json_decode(
            Storage::private()->get('/db/migrations.json'),
            true
        );

        foreach ($this->sortByDate(glob(app_path('/app/migrate/Migration_*.php'))) as $migration) {
            $migration = self::MIGRATION_DIR.basename(str_replace('.php', '', $migration));

            if (! isset($migrationContent[$migration]) || $isDump) {
                $migrationContent[$migration] = ['date' => date('Y-m-d H:i:s')];
                $migration = new $migration();
                $migration->up(new Schema(Config::get('app.model_path').$migration->model, $isDump));
            }
        }

        Storage::private()
            ->put('/db/migrations.json', json_encode($migrationContent, JSON_PRETTY_PRINT), true);
    }

    public function down()
    {
        $this->makeJsonFile(true);

        foreach (glob(app_path('/app/migrate/Migration_*.php')) as $migration) {
            $migration = self::MIGRATION_DIR.basename(str_replace('.php', '', $migration));
            $migration = new $migration();
            $migration->down(new Schema(Config::get('app.model_path').$migration->model));
        }

        Storage::private()->remove('/db/migrations.json');
    }

    public function dump()
    {
        $this->up(true);
    }

    public function db(string $database): void
    {
	    try {
		    Db::getInstance()->query('CREATE DATABASE '.$database)->execute();
	    } catch (\PDOException $e) {
	    	dd($e->getMessage());
	    }
    }

    private function makeJsonFile($replace = false)
    {
        Storage::private()->make('db')->put('/db/migrations.json', '{}', $replace);
    }

    private function sortByDate(array $files): array
    {
        $migrations = [];

        foreach ($files as $file) {
            $tmp = str_replace(app_path('/app/migrate/Migration_'), '', $file);
            $tmp = preg_replace('/[a-zA-Z__.]/', '', $tmp);
            $migrations[$tmp] = $file;
        }

        ksort($migrations);
        return $migrations;
    }
}
