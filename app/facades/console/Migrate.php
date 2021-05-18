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
        $this->{$args[0]}();
    }
    
    public function up()
    {
        $this->migrate->up();
        Console::output('Migration done!', 'green');
    }
    
    public function down()
    {
        $this->migrate->down();
        Console::output('Migration down!', 'green');
    }
    
    public function dump()
    {
        $this->migrate->dump();
        Console::output('Migration dump!', 'green');
    }
    
    public function make()
    {
	    array_shift($this->args);
        $this->migrate->make($this->args);
        Console::output('Migration create!', 'green');
    }
}
