<?php

if ((string) php_sapi_name() !== 'cli') {
    header('location: index.php');
}

require_once __DIR__.'/index.php';

$provider = explode(':', $argv[1]);

$controller = file_get_contents(app_path('app/facades/http/controller'));

$namespace = (string) $provider[0] === 'admin' ? 'Dash' : 'Index';

$controller = str_replace('CLASSNAME', ucfirst($provider[1]).'Controller', $controller);
$controller = str_replace('EXTENSION', ucfirst($namespace).'Controller', $controller);
$controller = str_replace('PATH', ucfirst($provider[0]), $controller);

file_put_contents(
    app_path('app/controllers/'.strtolower($provider[0]).'/'.ucfirst($provider[1]).'Controller.php'),
    $controller
);
