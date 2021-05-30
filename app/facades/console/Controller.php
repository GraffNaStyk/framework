<?php

namespace App\Facades\Console;

class Controller
{
	use FileCreator;
	
    private string $file;
    private string $name;
    
    public function __construct($args = [])
    {
	    $this->namespace = $args[0];
	    $this->name      = $args[1];
        $this->file = file_get_contents(app_path('app/facades/http/controller'));
        $this->make();
	    $this->putFile(
		    'app/controllers/'.ucfirst($this->namespace).'/'.ucfirst($this->name).'Controller.php',
		    $this->file
	    );
    }
    
    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Controller', $this->file);
        $this->file = str_replace('PATH', ucfirst($this->namespace), $this->file);
    }
}
