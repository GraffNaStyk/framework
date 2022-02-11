<?php

use App\Facades\Config\Config;
use App\Facades\Env\Env;
use App\Facades\Error\ErrorListener;

register_shutdown_function(fn () => ErrorListener::listen());
set_exception_handler(fn ($exception) => ErrorListener::exceptionHandler($exception));
spl_autoload_register(fn ($class) => App\Facades\Autoload\Autoload::run($class));

Env::set();
Config::init();
ErrorListener::setDisplayErrors();

$app = (new \App\Facades\Http\App(new \App\Facades\Http\Router\Router()));
$app->run();

if (php_sapi_name() !== 'cli') {
	if (Config::get('app.enable_api')) {
		require_once __DIR__ . '/routes/api.php';
	} else {
		require_once __DIR__ . '/routes/http.php';
	}

	$app->router->boot();
	$app->router->resolveRequest();
}
