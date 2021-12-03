<?php

namespace App\Events;

use App\Controllers\Admin\DashController;
use App\Controllers\Admin\LoginController;

class EventServiceProvider
{
    protected static array $listeners = [
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

    public static function getListener(string $when, string $listener): ?array
    {
        return static::$listeners[$when][$listener];
    }
}
