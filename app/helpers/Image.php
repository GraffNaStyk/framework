<?php

namespace App\Helpers;

class Image
{
    public static function get($path = '*')
    {
        return static::setUrl(glob(img_path($path), GLOB_BRACE));
    }

    public static function setUrl($images)
    {
        foreach ($images as $key => $img)
            $images[$key] = str_replace(app_path(), '', $img);
        return $images;
    }
}
