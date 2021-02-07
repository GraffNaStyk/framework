<?php

require_once app_path('app/facades/autoload/Autoload.php');

spl_autoload_register(function ($class) {
    App\Facades\Autoload\Autoload::run($class);
});

if ((bool) app('dev') === true) {
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR | E_PARSE);
} else {
    error_reporting(0);
}

register_shutdown_function(function () {
\App\Facades\Log\Log::handleError();
});

\App\Facades\Header\Header::set();
\App\Core\App::run();

if (php_sapi_name() !== 'cli') {
    require_once __DIR__ . '/routes/route.php';
}
