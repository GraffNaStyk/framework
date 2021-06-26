<?php

namespace App\Rules;

class LoginValidator
{
    public function getRule(array $optional = []): array
    {
        return [
            'name' => 'string|required|min_len:3|'.LoginValidator::class.':example',
            'password' => 'string|required|min_len:3',
            '__lang' => $this->getLang()
        ];
    }

    private function getLang(): array
    {
        return [
            'name' => [
                'required' => 'Custom error test',
	            'min_len' => 'test'
            ]
        ];
    }

    public static function example($item, $field)
    {

    }
}
