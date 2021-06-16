<?php

if (! file_exists(__DIR__.'/app/config/app.php')) {
    exit('No app config file');
}

if (! file_exists(app_path('app/config/.env'))) {
    exit('Cannot loaded environment file.');
}

if (! file_exists(vendor_path('autoload.php'))) {
    exit(require_once view_path('errors/install.php'));
}

define('app', require_once app_path('app/config/app.php'));

function css_path($path = null): string
{
    return __DIR__.'/public/css/'.$path;
}

function js_path($path = null): string
{
    return __DIR__.'/public/js/'.$path;
}

function view_path($path = null): string
{
    return __DIR__.'/public/views/'.$path;
}

function app_path($path = null): string
{
    return __DIR__.'/'.$path;
}

function storage_path($path = null): string
{
    return __DIR__.'/storage/'.$path;
}

function vendor_path($path = null): string
{
    return __DIR__.'/vendor/'.$path;
}

function path($path = null): string
{
    return __DIR__.'/'.$path;
}

function pd($item, $die = true): void
{
    echo '<pre>';
    print_r($item);
    echo '</pre>';
    if ($die) die();
}

function dd($item, $die = true): void
{
    echo '<pre>';
    var_dump($item);
    echo '</pre>';
    if ($die) die();
}

function app($key)
{
    return \App\Facades\Property\Get::check(app, $key);
}

function config($key)
{
    if (file_exists(app_path('app/config/'.$key.'.php'))) {
        return require_once app_path('app/config/'.$key.'.php');
    }
}

require_once app_path('app/bootstrap.php');