<?php

namespace App\Facades\Property;

class Property
{
	public static function exist($method, $item, $i)
	{
		if (is_object($method)) {
			if (! property_exists($method, $item[$i])) {
				return false;
			}
			
			$tmp = $method->{$item[$i]};
		} else {
			if (! isset($method[$item[$i]])) {
				return false;
			}
			
			$tmp = $method[$item[$i]];
		}
		
		return $tmp;
	}
}
