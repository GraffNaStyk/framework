<?php

namespace App\Facades\Console;

class Model
{
    use FileCreator;

    private string $file;
    private string $name;
    private string $table;

    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->table = $args[1];
        $this->file = file_get_contents(app_path('app/facades/migrations/model'));
        $this->make();
    }

    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name), $this->file);
        $this->file = str_replace('TABLE', mb_strtolower($this->table), $this->file);
        $this->putFile('app/model/'.ucfirst($this->name).'.php', $this->file);
    }
}