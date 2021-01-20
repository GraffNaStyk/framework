<?php
ini_set('memory_limit', '-1');

if (!file_exists(vendor_path('autoload.php'))) {
    exit(require_once view_path('errors/install.php'));
}

define('app', require_once app_path('app/config/app.php'));

register_shutdown_function(function () {
    \app\facades\log\Log::handlePhpError();
});

if ((bool) app('dev') === true) {
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR | E_PARSE);
} else {
    error_reporting(0);
}

spl_autoload_register(function ($class) {
    $classArr = explode('\\', $class);
    $className = end($classArr);
    
    array_pop($classArr);
    $classArr = array_map('strtolower', $classArr);
    $path = '';
    
    foreach ($classArr as $namespaces) {
        $path .= $namespaces.'/';
    }
    
    $className = rtrim($className, '/');

    if ((bool) file_exists(path($path . $className .'.php')) === true) {
        require_once path($path . $className .'.php');
    }

    if ((bool) file_exists(path($path . $className .'.inc')) === true) {
        require_once path($path . $className .'.inc');
    }
});

\App\Core\Config::run();

if (php_sapi_name() !== 'cli') {
    require_once __DIR__ . '/routes/route.php';
}
