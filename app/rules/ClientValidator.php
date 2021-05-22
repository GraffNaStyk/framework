<?php

namespace App\Rules;

class ClientValidator
{
    public function getRule(array $optional = []): array
    {
        return [
	        'testowyinput' => 'int|min:1|max:1',
            'name'         => 'required|min_len:4|string',
            'ftp_server'   => 'required|min_len:4',
            'ftp_user'     => 'required|min_len:4',
	        'www'          => 'float|required'
        ];
    }
}
