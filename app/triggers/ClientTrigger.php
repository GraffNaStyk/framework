<?php

namespace App\Triggers;

use App\Facades\Db\Db;
use App\Services\Abstraction\User\UserAuthenticateServiceInterface;

class ClientTrigger
{
	private Db $db;
	
	public function __construct(Db $db, UserAuthenticateServiceInterface $userAuthenticate)
	{
		$this->db = $db;
	}
	
    public function created()
    {

    }

    public function updated()
    {

    }

    public function deleted()
    {

    }
}
