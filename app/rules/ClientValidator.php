<?php

namespace App\Rules;

use App\Facades\Validator\Rules\Required;

class ClientValidator
{
    public function getRules(): array
    {
        return [
            'name' => [
	            new Required('no')
            ],
        ];
    }
}
