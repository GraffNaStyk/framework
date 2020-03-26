<?php namespace App\Facades\Uri;

class Uri
{
    public static function segment($string, $offset, $delimiter = '/')
    {
        $string = explode($delimiter, $string);

        if($offset == 'end' || $offset == 'last')
            return end($string);

        if(isset($string[$offset]))
            return $string[$offset];

        return false;
    }
}
