<?php

namespace App\Observers;


class UsersTrigger
{
    public function created()
    {

    }

    public function updated()
    {

    }

    public function deleted()
    {
		exit('im delete mordo');
    }
}
