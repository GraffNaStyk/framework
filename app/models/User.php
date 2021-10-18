<?php

namespace App\Models;

use App\Facades\Db\Model;

class User extends Model
{
    public static string $table = 'users';

    public static bool $trigger = true;
}
