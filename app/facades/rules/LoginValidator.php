<?php

namespace App\Facades\Rules;

class LoginValidator
{
    public function getRule(array $optional = []): array
    {
        return [
            'name' => 'string|required|min:3',
            'password' => 'string|required|min:3',
        ];
    }
}
