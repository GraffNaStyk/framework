<?php

namespace App\Core;

use App\Db\Db;
use App\Facades\Env\Env;
use App\Helpers\Loader;

final class App
{
    const PER_PAGE = 25;

    public function run(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
	
	    $environment = Env::set();

        if (! empty($environment)) {
            Db::init($environment);
        }

        Loader::set();
    }
}
