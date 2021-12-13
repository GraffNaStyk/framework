<?php

use App\Controllers\Admin\DashController;
use App\Controllers\Admin\LoginController;

return [
	'before' => [
	
	],
	'after' => [
		LoginController::class => [
			'check' => [
				\App\Events\UserLoginEvent::class
			]
		],
		DashController::class => [
			'index' => [
				\App\Events\UserLoginEvent::class
			]
		]
	]
];
