<?php

namespace App\Models;

use App\Attributes\Table\Table;
use App\Facades\Db\Model;

#[Table(table: 'rights')]
class Right extends Model
{
    public static string $table = 'rights';
}
