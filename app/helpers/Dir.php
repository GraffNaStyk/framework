<?php

namespace App\Helpers;


class Dir
{
    public static function create(string $path, int $permission = 0775): void
    {
        if (! is_dir($path)) {
            mkdir($path, $permission, true);
        }
    }
}
