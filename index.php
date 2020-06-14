<?php

if(!file_exists(__DIR__.'/app/config/app.php'))
    exit('No app config file');

if (session_status() === PHP_SESSION_NONE)
    session_start();

function public_path($path = null)
{
    return __DIR__ . '/public/'. $path;
}

function css_path($path = null)
{
    return __DIR__ . '/public/css/'. $path;
}

function js_path($path = null)
{
    return __DIR__ . '/public/js/'. $path;;
}

function view_path($path = null)
{
    return __DIR__ . '/public/views/'. $path;
}

function app_path($path = null)
{
    return __DIR__ . '/' . $path;
}

function storage_path($path = null)
{
    return __DIR__ . '/storage/' . $path;
}

function vendor_path($path = null)
{
    return __DIR__ . '/vendor/' . $path;
}

function pd($item)
{
    print_r($item);
    die;
}

function dd($item)
{
    var_dump($item);
    die;
}

function app($key)
{
    return app[$key];
}

require_once __DIR__.'/app/bootstrap.php';
