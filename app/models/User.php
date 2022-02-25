<?php

namespace App\Models;

use App\Attributes\Table\Table;
use App\Facades\Db\Model;

#[Table(table: 'users', isTriggered: true)]
class User extends Model
{
}
