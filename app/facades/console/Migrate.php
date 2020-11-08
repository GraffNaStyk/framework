<?php

namespace App\Facades\Console;

use App\Facades\Migrations\Migration;

class Migrate
{
    private object $migrate;
    
    public function __construct()
    {
        $this->migrate = new Migration();
    }
    
    public function up()
    {
        $this->migrate->up();
    }
    
    public function down()
    {
        $this->migrate->down();
    }
}
