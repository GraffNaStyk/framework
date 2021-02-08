<?php

namespace App\Core;

use App\Db\Db;
use App\Helpers\Loader;

abstract class App
{
    const PER_PAGE = 25;

    public static function run(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (phpversion() < 7.4) {
            trigger_error('Minimal version of php 7.4', E_USER_ERROR);
        }

        $env = require_once app_path('app/config/.env');
    
        if (! empty(array_filter($env['DB']))) {
            Db::init($env['DB']);
            unset($env['DB']);
        }
        
        Loader::set();
    }
}
