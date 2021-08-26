<?php

namespace App\Core;

use App\Facades\Db\Db;
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

        Env::set();
	    Db::init();
        Loader::set();
    }
}
