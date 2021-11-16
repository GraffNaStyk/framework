<?php

namespace App\Facades\Http;

use App\Facades\Config\Config;
use App\Facades\Helpers\CssLoader;
use App\Facades\Helpers\JavaScriptLoader;
use App\Facades\Http\Router\Router;

final class App
{
	use JavaScriptLoader;
	use CssLoader;
	
	public Router $router;
	const PER_PAGE = 25;
	
	public function __construct(Router $router)
	{
		$this->router = $router;
	}
	
	public function run(): void
	{
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}
	
	public function load()
	{
		if (Config::get('loader.autoload_js')) {
			JavaScriptLoader::bootScriptLoader();
			$this->loadJs('App.js');
			$this->enableJsAutoload();
		}
		
		if (Config::get('loader.autoload_css')) {
			CssLoader::bootCssLoader();
			$this->enableCssAutoload();
		}
	}
}
