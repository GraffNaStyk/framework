<?php

define('APP_START', microtime(true));

if (! is_readable(__DIR__ . '/app/config/app.php')) {
	exit('No app config file');
}

if (! is_readable(app_path('/app/config/.env'))) {
	exit('Cannot loaded environment file.');
}

if (! is_readable(vendor_path('/autoload.php'))) {
	exit(require_once view_path('/errors/install.php'));
}

function css_path($path = null): string
{
	return __DIR__ . '/public/css/' . ltrim($path, '/');
}

function js_path($path = null): string
{
	return __DIR__ . '/public/js/' . ltrim($path, '/');
}

function view_path($path = null): string
{
	return __DIR__ . '/app/views/' . ltrim($path, '/');
}

function app_path($path = null): string
{
	return __DIR__ . '/' . ltrim($path, '/');
}

function storage_path($path = null): string
{
	return __DIR__ . '/storage/' . ltrim($path, '/');
}

function assets_path($path = null): string
{
	return __DIR__ . '/public/assets/' . ltrim($path, '/');
}

function vendor_path($path = null): string
{
	return __DIR__ . '/vendor/' . ltrim($path, '/');
}

function path($path = null): string
{
	return __DIR__ . '/' . ltrim($path, '/');
}

function pd($item, $die = true): void
{
	echo '<pre>';
	print_r($item);
	echo '</pre>';

	if ($die) {
		die();
	}
}

require_once app_path('/app/bootstrap.php');
