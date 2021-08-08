<?php

namespace App\Facades\Property;

class Remove
{
    public static function remove($data, $item): array
    {
        $item = explode('.', $item);

        if (isset($item[3])) {
            unset($data[$item[0]][$item[1]][$item[2]][$item[3]]);
        } else if (isset($item[2])) {
            unset($data[$item[0]][$item[1]][$item[2]]);
        } else if (isset($item[1])) {
            unset($data[$item[0]][$item[1]]);
        } else {
            unset($data[$item[0]]);
        }

        return $data;
    }
}
