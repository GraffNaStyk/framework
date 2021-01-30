<?php

namespace App\Facades\Rules;

class UserValidator
{
    public function getRule(array $optional = []): array
    {
        return [
            'name' => 'required'
        ];
    }
}
