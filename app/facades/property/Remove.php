<?php

namespace App\Facades\Property;

class Remove
{
    public static function remove($iterable, $offset): array
    {
	    $offset = explode('.', $offset);

        if (isset($item[3])) {
            unset($iterable[$offset[0]][$offset[1]][$offset[2]][$offset[3]]);
        } else if (isset($offset[2])) {
            unset($iterable[$offset[0]][$offset[1]][$offset[2]]);
        } else if (isset($offset[1])) {
            unset($iterable[$offset[0]][$offset[1]]);
        } else {
            unset($iterable[$offset[0]]);
        }

        return $iterable;
    }
}
