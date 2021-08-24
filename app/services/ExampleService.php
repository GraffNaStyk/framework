<?php

namespace App\Services;

use App\Model\Client;
use App\Rules\ClientValidator;
use App\Rules\LoginValidator;

class ExampleService
{
    public function __construct(UserService $service, LoginValidator $validator, ClientValidator $clientValidator, Client $client)
    {

    }
}
