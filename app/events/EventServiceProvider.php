<?php

namespace App\Events;

use App\Controllers\Admin\ClientsController;

class EventServiceProvider
{
	protected static array $listeners = [
		ClientsController::class => [
			'index' => [
				ExampleEventProvider::class
			]
		]
	];
	
	public static function getListener(string $listener): ?array
	{
		return static::$listeners[$listener];
	}
}
