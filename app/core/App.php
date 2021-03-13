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

        $env = file_get_contents(app_path('app/config/.env'));
	    $environment = [];
        
        foreach (array_filter(explode(PHP_EOL, $env)) as $item) {
        	$item = explode('=', $item);
        	$environment[trim($item[0])] = trim($item[1]);
        }
    
        if (! empty($environment)) {
            Db::init($environment);
        }

        Loader::set();
    }
}
