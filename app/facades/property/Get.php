<?php

namespace App\Facades\Property;

class Get
{
    public static function check($iterable, $offset)
    {
        if (! is_array($offset)) {
            $offset = explode('.', $offset);
        }

        $count = array_key_last($offset);

        if ($count === 0) {
            $count = 1;
        }

        $i = 0;

        while ($i <= $count) {
            if (empty($offset[$i])) {
                return $res;
            }

            $res = Property::exist($i === 0 ? $iterable : $tmp, $offset, $i);

            if (! $res) {
                return false;
            }

            $tmp = $res;
            $i ++;
        }

        return $tmp;
    }
}
