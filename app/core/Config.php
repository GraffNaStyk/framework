<?php namespace App\Core;

use App\Db\Db;
use App\Helpers\Session;
use App\Facades\Csrf\Csrf;

abstract class Config
{
    public static function run(): void
    {
        if(!file_exists(app_path('app/config/.env')))
            trigger_error('Cannot loaded environment file.', E_USER_ERROR);

        if(phpversion() < 7.2)
            trigger_error('Minimal version of php 7.2', E_USER_ERROR);

        if(app['csrf'] && !Session::has('csrf') && ! View::isAjax())
            Csrf::generate();

        define('ENV', require_once app_path('app/config/.env'));

        Db::init(ENV['DB']);
    }
}
