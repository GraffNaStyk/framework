<?php

namespace App\Facades\Validator;

class Type
{
	public static function get($item)
	{
		if (is_null($item) || (string) $item === '') {
			return null;
		} else if (preg_match('/^[+-]?(\d*\.\d+([eE]?[+-]?\d+)?|\d+[eE][+-]?\d+)$/', str_replace([',', ' '], ['.', ''], $item))) {
			return (double) str_replace([',', ' '], ['.', ''], $item);
		} else if (is_numeric($item)) {
			return (int) $item;
		} else {
			return (string) trim($item);
		}
	}
}
