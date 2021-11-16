<?php

namespace App\Facades\Helpers;

use App\Facades\Http\Router\Router;
use App\Facades\Http\View;
use App\Helpers\UrlSetter;

trait JavaScriptLoader
{
	use UrlSetter;
	
	protected static ?string $url = null;
	
	public static function bootScriptLoader()
	{
		self::$url = static::setConfigUrl();
	}
	
	public function loadJs(... $scripts): void
	{
		$loaded = [];
		
		if (self::$url === null) {
			static::bootScriptLoader();
		}
		
		foreach ($scripts as $item) {
			$loaded[] = trim('<script type="application/javascript" src="'.
				self::$url.str_replace(app_path(), '', js_path($item)).'"></script>');
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
				$loaded[] = trim('<script type="application/javascript" src="'.
					self::$url.str_replace(app_path(), '', $item->getPathName()).'"></script>');
			}
		}

		View::set(['js' => $loaded]);
	}
	
	protected function enableJsAutoload(): void
	{
		if (is_readable(js_path('/'.Router::getClass().'/'.Router::getAction().'.js'))) {
			$loaded = trim('<script type="application/javascript" src="'.
				self::$url.str_replace(
					app_path(),
					'',
					js_path('/'.Router::getClass().'/'.Router::getAction())
				).
				'"></script>'
			);
		}
		
		if ($loaded !== null) {
			View::set(['js' => $loaded]);
		}
	}
}
