<?php

namespace App\Model;

use App\Facades\Db\Model;
use App\Facades\Db\Value;

class File extends Model
{
    public static string $table = 'files';

    public static function selectPath(): Value
    {
        return new Value("concat(dir, '', hash, '', ext) as file");
    }
}