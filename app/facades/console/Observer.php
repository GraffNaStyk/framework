<?php

namespace App\Facades\Console;

class Observer
{
	use FileCreator;
	
    private string $file;
    private string $name;
    
    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/http/observer'));
        $this->make();
    }
    
    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Observer', $this->file);

        if (! is_dir(app_path('app/observers/'))) {
            mkdir(app_path('app/observers/'), 0775, true);
        }

        $this->putFile('app/observers/'.ucfirst($this->name).'Observer.php', $this->file);
    }
}