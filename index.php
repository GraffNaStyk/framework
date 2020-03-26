<?php

if(!file_exists('App/Config/app.php'))
    exit('No app config file');

if (session_status() == PHP_SESSION_NONE)
    session_start();

function public_path($path = null)
{
    $fullPath = __DIR__ . '/public/'. $path;
    return $fullPath;
}

function img_path($path = null)
{
    $fullPath = __DIR__ . '/public/img/'. $path;
    return $fullPath;
}

function css_path($path = null)
{
    $fullPath = __DIR__ . '/public/css/'. $path;
    return $fullPath;
}

function js_path($path = null)
{
    $fullPath = __DIR__ . '/public/js/'. $path;
    return $fullPath;
}

function view_path($path = null)
{
    $fullPath = __DIR__ . '/public/views/'. $path;
    return $fullPath;
}

function app_path($path = null)
{
    $fullPath = __DIR__ . '/' . $path;
    return $fullPath;
}

function storage_path($path = null)
{
    $fullPath = __DIR__ . '/storage/' . $path;
    return $fullPath;
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

require_once './App/bootstrap.php';
