<?php

namespace App\Helpers;

use App\Facades\Http\View;

class Module
{
	public static function load(array $modules): void
	{
		$result = [];

		if (app('url') === '/') {
			$url = app('url');
		} else {
			$url = app('url').'/';
		}

		foreach ($modules as $module) {
			$result[] = trim('<script type="module" src="'.
				$url.str_replace(app_path(), '', js_path('modules/'.$module.'.js')).'"></script>');
		}

		View::set(['modules' => $result]);
	}
}
