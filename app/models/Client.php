<?php

namespace App\Models;

use App\Facades\Db\Model;

class Client extends Model
{
	public static string $table = 'clients';
	
	public static bool $trigger = true;
	
	public array $fields = [
		'www' => [
			'as' => 'twoooj_stary',
			'is' => '(int)',
		]
	];
}
