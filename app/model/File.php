<?php

namespace App\Model;

use App\Facades\Db\Eloquent\Value;
use App\Facades\Db\Model;

class File extends Model
{
    public static string $table = 'files';
    
    public static function selectPath(): Value
    {
        return new Value("concat(dir, '', hash, '', ext) as file");
    }
}
