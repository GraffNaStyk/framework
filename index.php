<?php

define('APP_START', microtime(true));

require_once __DIR__.'/app/facades/helpers/Functions.php';

if (! is_readable(__DIR__.'/app/config/app.php')) {
	exit('No app config file');
}

if (! is_readable(app_path('app/config/.env'))) {
	exit('Cannot loaded environment file.');
}

if (! is_readable(vendor_path('autoload.php'))) {
	exit(require_once view_path('errors/install.php'));
}

require_once app_path('app/bootstrap.php');
