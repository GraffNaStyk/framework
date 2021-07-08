<?php

namespace App\Facades\Console;

use App\Helpers\Dir;

class Event
{
    use FileCreator;

    private string $file;
    private string $name;

    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/files/event'));
        $this->make();
    }

    public function make(): void
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Event', $this->file);
        Dir::create(app_path('app/events/'));
        $this->putFile('app/events/'.ucfirst($this->name).'Event.php', $this->file);
    }
}
