<?php

define('APP_START', microtime(true));

if (! is_readable(__DIR__.'/../app/config/app.php')) {
	exit('No app config file');
}

if (! is_readable(__DIR__.'/../app/config/.env')) {
	exit('Cannot loaded environment file.');
}

if (! is_readable(__DIR__.'/../vendor/autoload.php')) {
	exit(require_once __DIR__.'/../app/views/errors/install.php');
}

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../app/bootstrap.php';
