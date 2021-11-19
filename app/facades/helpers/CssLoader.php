<?php

namespace App\Facades\Helpers;

use App\Facades\Http\Router\Router;
use App\Facades\Http\View;
use App\Helpers\UrlSetter;

trait CssLoader
{
	use UrlSetter;
	
	protected static ?string $url = null;
	
	public static function bootCssLoader()
	{
		self::$url = static::setConfigUrl();
	}
	
	public function loadCss(... $scripts): void
	{
		$loaded = [];
		
		if (self::$url === null) {
			static::bootScriptLoader();
		}
		
		foreach ($scripts as $item) {
			$loaded[] = trim('<link rel="stylesheet" href="'.
				self::$url.str_replace(app_path(), '', css_path($item)).'">');
		}
		
		View::set(['css' => $loaded]);
	}
	
	public function loadCssFromDir(string $dir): void
	{
		if (! is_dir(css_path($dir))) {
			throw new \LogicException('Directory '.css_path($dir).' not exist!');
		}
		
		$loaded = [];

		foreach (new \DirectoryIterator(css_path($dir)) as $item) {
			if ($item->getExtension() === 'css') {
				$loaded[] = trim('<link rel="stylesheet" href="'.
					self::$url.str_replace(app_path(), '', $item->getPathName()).'">');
			}
		}
		
		View::set(['css' => $loaded]);
	}
	
	protected function enableCssAutoload(): void
	{
		if (is_readable(css_path(Router::getAlias().'/'.Router::getClass().'/'.Router::getAction().'.js'))) {
			$loaded = trim('<link rel="stylesheet" href="'.
				self::$url.str_replace(
					app_path(),
					'',
					css_path(Router::getAlias().'/'.Router::getClass().'/'.Router::getAction())
				).
				'.css">'
			);
		}
		
		if ($loaded !== null) {
			View::set(['css' => [$loaded]]);
		}
	}
}
