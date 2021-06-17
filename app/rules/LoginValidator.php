<?php

namespace App\Rules;

class LoginValidator
{
    public function getRule(array $optional = []): array
    {
        return [
            'name' => 'string|required|min:3|'.LoginValidator::class.':example',
            'password' => 'string|required|min:3',
            '__lang' => $this->getLang()
        ];
    }

    private function getLang(): array
    {
        return [
            'name' => [
                'required' => 'Custom error test'
            ]
        ];
    }

    public static function example($item, $field)
    {

    }
}