<?php

namespace App\Models;

use App\Attributes\Table\Table;
use App\Facades\Db\Model;

#[Table(table: 'files')]
class File extends Model
{
}
