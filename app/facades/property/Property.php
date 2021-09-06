<?php

namespace App\Facades\Property;

class Property
{
    public static function exist($iterable, iterable $offset, int $i)
    {
        if (is_object($iterable)) {
            if (! property_exists($iterable, $offset[$i])) {
                return false;
            }

            $tmp = $iterable->{$offset[$i]};
        } else {
            if (! isset($iterable[$offset[$i]])) {
                return false;
            }

            $tmp = $iterable[$offset[$i]];
        }

        return $tmp;
    }
}
