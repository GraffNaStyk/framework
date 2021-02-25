<?php

namespace App\Core;

use App\Db\Db;
use App\Helpers\Loader;

final class App
{
    const PER_PAGE = 25;

    public function run(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $env = require_once app_path('app/config/.env');
    
        if (! empty(array_filter($env['DB']))) {
            Db::init($env['DB']);
            unset($env['DB']);
        }

        Loader::set();
    }
}
