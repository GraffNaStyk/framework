<?php

namespace App\Facades\Property;

class Has
{
    public static function check($iterable, $offset): bool
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
            if ((string) $offset[$i] === '' || $offset[$i] === null) {
                return true;
            }

            $res = Property::exist($i === 0 ? $iterable : $tmp, $offset, $i);

            if (! $res) {
                return false;
            }

            $tmp = $res;
            $i ++;
        }
        return true;
    }
}
