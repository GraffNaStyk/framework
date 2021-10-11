<?php

namespace App\Helpers;

use App\Facades\Config\Config;

trait UrlSetter
{
	public static function setConfigUrl(): string
	{
		if (Config::get('app.url') === '/') {
			return Config::get('app.url');
		}
		
		return Config::get('app.url').'/';
	}
}
