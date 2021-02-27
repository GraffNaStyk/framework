<?php

namespace App\Helpers;

use App\Facades\Http\Router\Router;

class Loader
{
	protected static array $loaded = [];
	
	private static string $url;
	
	public static function set(): void
	{
		self::$loaded = app('is_loaded');
		
		if (app('url') === '/') {
			self::$url = app('url');
		} else {
			self::$url = app('url').'/';
		}
	}
	
	public static function css(): string
	{
		$folder = Router::getAlias();
		
		if (app('dev')) {
			$cssArr = glob(css_path("$folder/*.css"), GLOB_BRACE);
			
			$rebuild = false;
			$mtime = filemtime(js_path('build/'.$folder.'/result.css'));
			
			foreach ($cssArr as $item) {
				if (filemtime($item) > $mtime) {
					$rebuild = true;
					break;
				}
			}
			
			if ($rebuild) {
				unlink(css_path('build/'.$folder.'/result.css'));
				$cssString = null;
				
				foreach ($cssArr as $key => $css) {
					if ((bool) is_readable($css)) {
						$cssString .= preg_replace('/\s\s+/', '', file_get_contents($css));
					}
				}
				
				file_put_contents(css_path('build/'.$folder.'/result.css'), $cssString);
			}
		}
		
		$cssString = trim('<link rel="stylesheet" href="'.
				self::$url.str_replace(app_path(), '', css_path('build/'.$folder.'/result.css')).'">').PHP_EOL;
		
		foreach (self::$loaded['css'] as $val) {
			$cssString .= self::getFile($val, 'css');
		}
		
		return $cssString;
	}
	
	public static function js(): string
	{
		$folder = Router::getAlias();
		
		if (app('dev')) {
			$jsArr = glob(js_path("$folder/*.js"), GLOB_BRACE);
			$rebuild = false;
			$mtime = filemtime(js_path('build/'.$folder.'/result.js'));
			
			foreach ($jsArr as $item) {
				if (filemtime($item) > $mtime) {
					$rebuild = true;
					break;
				}
			}
			
			if ($rebuild) {
				unlink(js_path('build/'.$folder.'/result.js'));
				$jsString = null;
				
				foreach ($jsArr as $key => $js) {
					if ((bool) is_readable($js)) {
						$jsString .= preg_replace('/\s\s+/', ' ', file_get_contents($js)).' ; ';
					}
				}
				
				$jsString = str_replace("import * as App from '../app.js';",'', $jsString);
				file_put_contents(
					js_path('build/'.$folder.'/result.js'),
					"import * as App from '../../app.js'; ".$jsString
				);
			}
		}
		
		$jsString = trim('<script type="module" src="'.
				self::$url.str_replace(app_path(), '', js_path('build/'.$folder.'/result.js')).'"></script>').PHP_EOL;
		
		foreach (self::$loaded['js'] as $val) {
			$jsString .= self::getFile($val, 'js');
		}
		
		return $jsString;
	}
	
	private static function getFile($name, $ext): string
	{
		$path = $ext === 'css'
			? css_path($name.'.css')
			: js_path($name.'.js');
		
		if (is_readable($path)) {
			if ($ext === 'css') {
				return trim('<link rel="stylesheet" href="'.
					self::$url.str_replace(app_path(), '', $path).'">'.PHP_EOL);
			}
			
			return trim('<script src="'.
				self::$url.str_replace(app_path(), '', $path).'"></script>'.PHP_EOL);
		}
		
		return '';
	}
}
