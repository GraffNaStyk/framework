<?php

return [
	\App\Services\Abstraction\User\UserAuthenticateInterface::class => \App\Services\User\UserAuthenticateService::class,
	\App\Repositories\Abstraction\HouseInterface::class => \App\Repositories\HouseRepository::class
];
