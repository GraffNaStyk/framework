<?php

namespace App\Facades\Helpers;

use App\Facades\Http\Router\Router;
use App\Facades\Http\View;
use App\Facades\Url\Url;
use App\Helpers\UrlSetter;

trait JavaScriptLoader
{
	public function loadJs(... $scripts): void
	{
		$loaded = [];
		
		foreach ($scripts as $item) {
			$loaded[] = trim('<script type="application/javascript" src="'.Url::full().'/'.str_replace(app_path(), '', js_path($item)).'"></script>');
		}
		
		View::set(['js' => $loaded]);
	}
	
	public function loadJsFromDir(string $dir): void
	{
		if (! is_dir(js_path($dir))) {
			throw new \LogicException('Directory '.js_path($dir).' not exist!');
		}
		
		$loaded = [];
		
		foreach (new \DirectoryIterator(js_path($dir)) as $item) {
			if ($item->getExtension() === 'js') {
				$loaded[] = trim('<script type="application/javascript" src="'.Url::full().'/'.str_replace(app_path(), '', $item->getPathName()).'"></script>');
			}
		}

		View::set(['js' => $loaded]);
	}
	
	protected function enableJsAutoload(): void
	{
		$loaded = null;
		
		if (is_readable(js_path(Router::getAlias().'/'.Router::getClass().'/'.Router::getAction().'.js'))) {
			$loaded = trim('<script type="application/javascript" src="'.
				Url::full().'/'.str_replace(
					app_path(),
					'',
					js_path(Router::getAlias().'/'.Router::getClass().'/'.Router::getAction())
				).
				'.js"></script>'
			);
		}
		
		if ($loaded !== null) {
			View::set(['js' => [$loaded]]);
		}
	}
}
