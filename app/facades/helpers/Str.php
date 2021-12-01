<?php

namespace App\Facades\Helpers;

class Str
{
	public static function hash(int $length): string
	{
		return bin2hex(random_bytes($length));
	}

    public static function getUniqueStr(string $model, string $column = 'hash', int $length = 50): string
    {
        do {
            $hash  = self::hash($length);
            $check = $model::select([$column])->where($column, '=', $hash)->first();
        } while (! empty($check));

        return $hash;
    }
}
