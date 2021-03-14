<?php

namespace App\Model;

use App\Db\Eloquent\Value;
use App\Db\Model;

class File extends Model
{
    public static string $table = 'files';
    
    public static function selectPath(): Value
    {
        return new Value("concat(dir, '', hash, '', ext) as file");
    }
}
