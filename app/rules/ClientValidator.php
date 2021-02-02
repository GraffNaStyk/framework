<?php

namespace App\Rules;

class ClientValidator
{
    public function getRule(array $optional = []): array
    {
        return [
            'name' => 'required|min:4',
            'ftp_server' => 'required|min:4',
            'ftp_user' => 'required|min:4',
        ];
    }
}
