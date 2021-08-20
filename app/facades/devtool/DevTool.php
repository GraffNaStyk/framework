<?php

namespace App\Facades\Devtool;

class DevTool
{
	public static function boot(): string
	{
		$time = round(microtime(true) - APP_START, 4);
		require_once app_path('app/facades/devtool/index.php');

		return ob_get_clean();
	}
}
