<?php

namespace App\Helpers;


class Dir
{
	public static function create(string $path): void
	{
		if (! is_dir($path)) {
			mkdir($path, 0775, true);
		}
	}
}
