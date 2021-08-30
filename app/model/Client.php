<?php

namespace App\Model;

use App\Facades\Db\Model;

class Client extends Model
{
	public static string $table = 'clients';
	
	public static bool $trigger = true;
}
