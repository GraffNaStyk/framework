<?php

namespace App\Facades\Console;

class Filter
{
    use FileCreator;

    private string $file;
    private string $name;

    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/files/filter'));
        $this->make();
    }

    public function make(): void
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Filter', $this->file);
        $this->putFile('app/filters/'.ucfirst($this->name).'Filter.php', $this->file);
    }
}
