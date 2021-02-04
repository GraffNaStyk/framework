<?php

namespace App\Facades\Console;

class Model
{
    private string $file;
    private string $name;
    private string $table;
    
    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->table = $args[1];
        $this->file = file_get_contents(app_path('app/facades/migrations/model'));
    }
    
    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name), $this->file);
        $this->file = str_replace('TABLE', mb_strtolower($this->table), $this->file);
    
        if (file_put_contents(
            app_path('app/model/'.ucfirst($this->name).'.php'),
            $this->file
        )) {
            Console::output(
                'Model '.$this->name.' successfully created',
                'green'
            );
        }
    }
}
