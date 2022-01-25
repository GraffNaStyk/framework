<?php

namespace App\Facades\Error;

use App\Facades\Config\Config;
use App\Facades\Log\Log;

class ErrorListener
{
	public static function listen(): void
	{
		$error = error_get_last();
		
		if (! empty($error)
			&& in_array($error['type'], [E_USER_ERROR, E_ERROR, E_PARSE, E_COMPILE_ERROR, E_CORE_ERROR])
		) {
			throw new \Exception($error);
		}
	}

	public static function exceptionHandler(\Throwable $exception)
	{
		if (Config::get('app.error_listener')) {
			if (! class_exists(Config::get('app.error_listener'))) {
				exit('Your custom exception listener " '.Config::get('app.error_listener').'" not exist!');
			}
			
			(new (Config::get('app.error_listener')))->listen($exception);
		} else if (Config::get('app.dev')) {
			(new LogErrorFormatter($exception))->format();
		} else {
			Log::custom('php', [
					'line'    => $exception->getLine(),
					'file'    => $exception->getFile(),
					'trace'   => $exception->getTraceAsString(),
					'code'    => $exception->getCode(),
					'message' => $exception->getMessage()
				]
			);
			exit (require_once view_path('errors/error.php'));
		}
	}

	public static function setDisplayErrors(): void
	{
		if (Config::get('app.dev')) {
			ini_set('display_startup_errors', 1);
			error_reporting(Config::get('app.reporting_levels'));
		} else {
			ini_set('display_startup_errors', 0);
			error_reporting(0);
		}
	}
}
