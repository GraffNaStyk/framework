<?php

namespace App\Facades\Console;

class Service
{
    use FileCreator;

    private string $file;
    private string $name;

    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/files/service'));
        $this->make();
    }

    public function make(): void
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Service', $this->file);
        $this->putFile('app/services/'.ucfirst($this->name).'Service.php', $this->file);
    }
}
