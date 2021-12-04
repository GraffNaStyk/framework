<?php

use App\Controllers\Admin\DashController;
use App\Controllers\Admin\LoginController;

return [
	'before' => [
	
	],
	'after' => [
		LoginController::class => [
			'check' => [
				UserLoginEvent::class
			]
		],
		DashController::class => [
			'index' => [
				UserLoginEvent::class
			]
		]
	]
];
