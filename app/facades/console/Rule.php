<?php

namespace App\Facades\Console;

class Rule
{
    use FileCreator;

    private string $file;
    private string $name;

    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/files/rule'));
        $this->make();
    }

    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Validator', $this->file);
        $this->putFile('app/rules/'.ucfirst($this->name).'Validator.php', $this->file);
    }
}
