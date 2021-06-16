<?php

namespace App\Facades\Property;

class Set
{
    public static function set($where, $data, $item)
    {
        $item = explode('.', $item);

        if (isset($item[3])) {
            $where[$item[0]][$item[1]][$item[2]][$item[3]] = $data;
        } else if (isset($item[2])) {
            $where[$item[0]][$item[1]][$item[2]] = $data;
        } else if (isset($item[1])) {
            $where[$item[0]][$item[1]] = $data;
        } else {
            $where[$item[0]] = $data;
        }

        return $where;
    }
}