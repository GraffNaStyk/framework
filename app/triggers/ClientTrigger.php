<?php

namespace App\Triggers;

use App\Facades\Db\Db;

class ClientTrigger
{
	private Db $db;
	
	public function __construct(Db $db)
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
