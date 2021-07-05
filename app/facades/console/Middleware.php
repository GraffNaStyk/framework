<?php

namespace App\Facades\Console;

class Middleware
{
    use FileCreator;

    private string $file;
    private string $name;

    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/files/middleware'));
        $this->make();
    }

    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name), $this->file);
        $this->putFile('app/controllers/middleware/'.ucfirst($this->name).'.php', $this->file);
    }
}
