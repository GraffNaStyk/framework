<?php
namespace App\Facades\Migrations;

use App\Helpers\Storage;

class Migration
{
    protected static array $argv = [];

    protected static array $canDo = [
        'make', 'up', 'down', 'dump'
    ];

    public static function dispatch(&$argv)
    {
        self::$argv = $argv;
        return new self();
    }

    public function do(string $what): bool
    {
        if (in_array($what, self::$canDo) === true
            && (string) $what === self::$argv[1]
        ) {
            return true;
        }

        return false;
    }

    public function make()
    {
        $migration = file_get_contents(app_path('app/facades/migrations/migration'));
        $migration = str_replace('CLASSNAME', 'Migration_'.self::$argv[2].'_'.date('Y_m_d__H_i'), $migration);
        $migration = str_replace('MODEL', self::$argv[2], $migration);

        if (is_dir(app_path('app/db/migrate/')) === false) {
            mkdir(app_path('app/db/migrate/'), 0775, true);
        }

        file_put_contents(app_path('app/db/migrate/Migration_'.self::$argv[2].'_'.date('Y_m_d__H_i').'.php'), "<?php ".$migration);

        if(file_exists(app_path('app/model/'.ucfirst(self::$argv[2]).'.php')) === false) {
            $model = file_get_contents(app_path('app/facades/migrations/model'));
            $model = str_replace('CLASSNAME', ucfirst(self::$argv[2]), $model);
            $model = str_replace('TABLE', self::$argv[3], $model);
            file_put_contents(app_path('app/model/'.ucfirst(self::$argv[2]).'.php'), "<?php ".$model);
        }
    }

    public function up(bool $isDump = false)
    {
        $this->makeJsonFile();
        $json = Storage::disk('private')->get('db/migrations.json');
        $migrationContent = (array) json_decode(storage_path($json[0]), true);
        foreach (glob(app_path('app/db/migrate/Migration_*.php')) as $migration) {
            $migration = 'App\\Db\\Migrate\\'.basename(str_replace('.php','', $migration));
            if (array_key_exists($migration, $migrationContent) === false) {
                $migrationContent[$migration] = ['date' => date('Y-m-d H:i:s')];
                $migration = new $migration();
                $migration->up(new Schema(app['model-provider'].$migration->model, $isDump));
            }
        }
        Storage::disk('private')->put('db/migrations.json', json_encode($migrationContent), true);
    }

    public function down()
    {
        $this->makeJsonFile(true);
        foreach (glob(app_path('app/db/migrate/Migration_*.php')) as $migration) {
            $migration = 'App\\Db\\Migrate\\'.basename(str_replace('.php','', $migration));
            $migration = new $migration();
            $migration->down(new Schema(app['model-provider'].$migration->model));
        }
        Storage::disk('private')->remove('db/migrations.json');
    }

    public function dump()
    {
        $this->up(true);
    }

    private function makeJsonFile($replace=false)
    {
        Storage::disk('private')->make('db');
        Storage::disk('private')->put('db/migrations.json', '{}', $replace);
    }
}
