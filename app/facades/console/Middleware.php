<?php

namespace App\Facades\Console;

class Middleware
{
    private string $file;
    private string $name;
    
    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/http/middleware'));
    }
    
    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name), $this->file);
        if (file_put_contents(
            app_path('app/controllers/middleware/'.ucfirst($this->name).'.php'),
            $this->file
        )) {
            Console::output('Middleware created successfully!', 'green');
        }
    }
}
