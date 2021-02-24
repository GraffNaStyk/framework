<?php

require_once app_path('app/facades/autoload/Autoload.php');
require_once vendor_path('autoload.php');

spl_autoload_register(fn ($class) => App\Facades\Autoload\Autoload::run($class));

\App\Facades\Log\Log::setDisplayErrors();

register_shutdown_function(fn () => \App\Facades\Log\Log::handleError());

\App\Facades\Header\Header::set();

(new \App\Core\App())->run();

if (php_sapi_name() !== 'cli') {
    require_once __DIR__ . '/routes/route.php';
}
