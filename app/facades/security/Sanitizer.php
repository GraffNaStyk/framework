<?php

namespace App\Facades\Security;

use App\Facades\Config\Config;
use App\Facades\Validator\Type;

class Sanitizer
{
	public function clear($item)
	{
		if (! is_numeric($item)) {
			$item = (string) urldecode($item);
		}
		
		if (Config::get('app.security.enabled')) {
			$item = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $item);
			$item = preg_replace('/<noscript\b[^>]*>(.*?)<\/noscript>/is', '', $item);
			$item = preg_replace('/<a(.*?)>(.+)<\/a>/', '', $item);
			$item = preg_replace('/<iframe(.*?)>(.+)<\/iframe>/', '', $item);
			$item = preg_replace('/<img (.*?)>/is', '', $item);
			$item = preg_replace('/<embed (.*?)>/is', '', $item);
			$item = preg_replace('/<link (.*?)>/is', '', $item);
			$item = preg_replace('/<video (.*?)>(.+)<\/video>/', '', $item);
		}
		
		$item = filter_var($item, FILTER_UNSAFE_RAW, FILTER_FLAG_STRIP_HIGH);
		$item = strtr(
			$item,
			'???????��������������������������������������������������������������',
			'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy'
		);
		
		$item = preg_replace('/(;|\||`|&|^|{|}|[|]|\)|\()/i', '', $item);
		$item = preg_replace('/(\)|\(|\||&)/', '', $item);
		$item = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $item);
		
		return Type::get($item);
	}
}
