<?php

use App\Facades\Env\Env;

return [
	'default' => [
		'user'     => Env::get('DEFAULT_DB_USER'),
		'password' => Env::get('DEFAULT_DB_PASS'),
		'host'     => Env::get('DEFAULT_DB_HOST'),
		'database' => Env::get('DEFAULT_DB_NAME'),
	]
];
