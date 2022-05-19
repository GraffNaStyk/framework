<?php

namespace App\Controllers\Storage;

use App\Controllers\Controller;
use App\Facades\Config\Config;
use App\Facades\Http\Response;

class StorageController extends Controller
{
	public function display(string $hash = null): ?Response
	{
		$path = array_filter(explode('/', $hash));
		$name = end($path);
		array_pop($path);
		
		$disk = storage_path(implode('/', $path));
		
		if (is_readable($disk.'/'.$name) && is_file($disk.'/'.$name)) {
			return (new Response())->file($disk.'/'.$name);
		} else if (Config::get('app.no_photo_assets_img') !== null) {
			return (new Response())->file(assets_path(Config::get('app.no_photo_assets_img')));
		}
		
		return null;
	}
}
