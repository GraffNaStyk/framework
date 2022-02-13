<?php

namespace App\Triggers;

use App\Facades\Db\Db;

class ClientTrigger
{
	public function __construct(private Db $db)
	{
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
