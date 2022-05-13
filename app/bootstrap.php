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
ErrorListener::setRouter($app->router);
$app->run();
