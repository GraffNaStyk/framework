<?php

ini_set('memory_limit', '-1');

if(!file_exists(vendor_path('autoload.php'))) {
    exit(require_once view_path('errors/install.php'));
}

define('app', require_once app_path('app/config/app.php'));

if ((bool) app['dev'] === true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
else {
    ini_set('error_log', storage_path('private/logs/php_' . date('d-m-Y') . '.log'));
    ini_set('log_errors', true);
}

spl_autoload_register(function ($class) {
    $classArr = explode('\\', $class);
    $className = end($classArr);

    array_pop($classArr);
    $classArr = array_map('strtolower', $classArr);

    $path = '';
    foreach ($classArr as $namespaces)
        $path .= $namespaces.'/';

    $className = rtrim($className, '/');

    if(file_exists($path . $className .'.php'))
        require_once $path . $className .'.php';

    if((bool) app['dev'] === true && !file_exists($path))
        trigger_error('Cannot loaded file ' . $path . ', file not exist.', E_USER_ERROR);
});

\App\Core\Config::run();

if(php_sapi_name() !== 'cli') {
    require_once __DIR__.'/routes/route.php';
}




