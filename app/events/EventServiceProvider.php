<?php

namespace App\Events;

use App\Controllers\Admin\LoginController;

class EventServiceProvider
{
    protected static array $listeners = [
        LoginController::class => [
            'check' => [
                UserLoginEvent::class
            ]
        ]
    ];

    public static function getListener(string $listener): ?array
    {
        return static::$listeners[$listener];
    }
}
