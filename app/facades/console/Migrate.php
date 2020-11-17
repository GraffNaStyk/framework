<?php

namespace App\Facades\Console;

use App\Facades\Migrations\Migration;

class Migrate
{
    private object $migrate;
    private array $args;
    
    public function __construct($args)
    {
        $this->args = $args;
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
    
    public function dump()
    {
        $this->migrate->dump();
    }
    
    public function make()
    {
        $this->migrate->make($this->args);
    }
}
