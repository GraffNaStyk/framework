<?php
ini_set('memory_limit', '-1');

if (!file_exists(vendor_path('autoload.php'))) {
    exit(require_once view_path('errors/install.php'));
}

define('app', require_once app_path('app/config/app.php'));

register_shutdown_function('fatalErrorHandler');

if ((bool) app['dev'] === true) {
    ini_set('display_startup_errors', 1);
    error_reporting(E_ERROR | E_PARSE);
} else {
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

function fatalErrorHandler () {
    $lastError = error_get_last();
    if (!empty($lastError)) {
        if ($lastError['type'] === E_ERROR || $lastError['type'] === E_USER_ERROR || $lastError['type'] === E_PARSE) {
            header("HTTP/1.0 500 Method Not Allowed");
            http_response_code(500);
        
            if (app['dev'] === false) {
                exit (require_once view_path('errors/fatal.php'));
            } else {
                exit (require_once view_path('errors/fatal-dev.php'));
            }
        }
    }
}
