<?php

namespace App\Facades\Console;

class Rule
{
    private string $file;
    private string $name;
    
    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/http/rule'));
        $this->make();
    }
    
    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Validator', $this->file);
        if (file_put_contents(
            app_path('app/rules/'.ucfirst($this->name).'Validator.php'),
            $this->file
        )) {
            Console::output('Rule created successfully!', 'green');
        }
    }
}
