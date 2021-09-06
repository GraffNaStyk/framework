<?php

use App\Facades\Config\Config;
use App\Facades\Header\Header;
use App\Facades\Log\Log;

require_once app_path('app/facades/autoload/Autoload.php');
require_once vendor_path('autoload.php');

spl_autoload_register(fn ($class) => App\Facades\Autoload\Autoload::run($class));

Config::init();
Log::setDisplayErrors();

register_shutdown_function(fn () => Log::handleError());

Header::set();

$app = (new \App\Core\App(new \App\Facades\Http\Router\Router()));
$app->run();

if (php_sapi_name() !== 'cli') {
    if (php_sapi_name() === 'cli-server') {
        define('API', true);
        require_once __DIR__.'/routes/api.php';
    } else {
        require_once __DIR__.'/routes/http.php';
    }
    
	$app->router->boot();
}
