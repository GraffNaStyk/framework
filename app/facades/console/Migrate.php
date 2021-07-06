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
        $this->{$args[0]}($args[1] ?? null);
    }

    public function up(): void
    {
        $this->migrate->up();
        Console::output('Migration done!', 'green');
    }

    public function down(): void
    {
        $this->migrate->down();
        Console::output('Migration down!', 'green');
    }

    public function dump(): void
    {
        $this->migrate->dump();
        Console::output('Migration dump!', 'green');
    }

    public function make(): void
    {
        array_shift($this->args);
        $this->migrate->make($this->args);
        Console::output('Migration create!', 'green');
    }

    public function db(string $database): void
    {
    	$this->migrate->db($database);
    }
}
