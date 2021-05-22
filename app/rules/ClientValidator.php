<?php

namespace App\Rules;

class ClientValidator
{
    public function getRule(array $optional = []): array
    {
        return [
            'name'         => 'required|min_len:4|string',
            'ftp_server'   => 'required|min_len:4',
            'ftp_user'     => 'required|min_len:4',
	        'www'          => 'required'
        ];
    }
}
