<?php

use App\Facades\Config\Config;
use App\Facades\Env\Env;
use App\Facades\Log\Log;

register_shutdown_function(fn () => Log::handleError());

require_once app_path('/app/facades/autoload/Autoload.php');
require_once vendor_path('/autoload.php');

spl_autoload_register(fn ($class) => App\Facades\Autoload\Autoload::run($class));

Log::setDisplayErrors();
Env::set();
Config::init();

$app = (new \App\Facades\Http\App(new \App\Facades\Http\Router\Router()));
$app->run();

if (php_sapi_name() !== 'cli') {
	if (php_sapi_name() === 'cli-server') {
		define('API', true);
		require_once __DIR__ . '/routes/api.php';
	} else {
		require_once __DIR__ . '/routes/http.php';
	}
	
	$app->router->boot();
}
