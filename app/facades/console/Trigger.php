<?php

namespace App\Facades\Console;

use App\Helpers\Dir;

class Trigger
{
    use FileCreator;

    private string $file;
    private string $name;

    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/files/trigger'));
        $this->make();
    }

    public function make(): void
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Trigger', $this->file);
        Dir::create(app_path('app/triggers/'));
        $this->putFile('app/triggers/'.ucfirst($this->name).'Trigger.php', $this->file);
    }
}
