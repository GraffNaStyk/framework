<?php

namespace App\Helpers;

use App\Facades\Config\Config;
use App\Facades\Http\View;

class Module
{
	public static function load(string ...$modules): void
	{
		$result = [];
		
		if (Config::get('app.url') === '/') {
			$url = Config::get('app.url');
		} else {
			$url = Config::get('app.url').'/';
		}

		foreach ($modules as $module) {
			if (is_readable(js_path('modules/'.$module.'.js'))) {
				$result[] = trim('<script type="module" src="'.
					$url.str_replace(app_path(), '', js_path('modules/'.$module.'.js')).'"></script>');
			}
		}

		if (! empty($result)) {
			View::set(['modules' => $result]);
		}
	}
}
