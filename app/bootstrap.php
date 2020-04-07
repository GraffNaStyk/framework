<?php

ini_set('memory_limit', '-1');

set_error_handler('error_handler');

function error_handler($errorNumber, $errorStr, $file, $line)
{
    echo '<div style="border: 1px solid hsla(0,0%,80%,.5); padding: 10px; font-size: 18px;
    color: #282828; background: #fdfdfd; margin-bottom: 5px; border-radius: 5px;">';
    echo "<b>Php:</b> [" . PHP_VERSION . " (" . PHP_OS . ")]<br />";
    echo "<b>File:</b> [$file] <br />";
    echo "<b>Line:</b> [$line] <br />";
    echo "<b>Error:</b> [code: $errorNumber] $errorStr";
    echo '</div>';
}

define('app', require_once app_path('app/config/app.php'));

if(app['dev'] == true) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
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

    if(app['dev'] && ! file_exists($path))
        trigger_error('Cannot loaded file ' . $path . ', file not exist.', E_USER_ERROR);
});

\App\Core\Config::run();

require_once __DIR__.'/routes/route.php';
