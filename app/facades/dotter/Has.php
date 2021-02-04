<?php

namespace App\Facades\Dotter;

class Has
{
    public static function check($method, $item = [])
    {
        if (isset($item[3])) {
            if (isset($method[$item[0]][$item[1]][$item[2]][$item[3]])
                && !empty($method[$item[0]][$item[1]][$item[2]][$item[3]])
            ) {
                return true;
            } else {
                return false;
            }
        } else if (isset($item[2])) {
            if (isset($method[$item[0]][$item[1]][$item[2]]) && !empty($method[$item[0]][$item[1]][$item[2]])) {
                return true;
            } else {
                return false;
            }
        } else if (isset($item[1])) {
            if (isset($method[$item[0]][$item[1]]) && !empty($method[$item[0]][$item[1]])) {
                return true;
            } else {
                return false;
            }
        } else {
            if (isset($method[$item[0]]) && !empty($method[$item[0]])) {
                return true;
            } else {
                return false;
            }
        }
    }
}
