<?php

namespace App\Facades\Console;

class Controller
{
    private string $file;
    private string $name;
    
    public function __construct($args = [])
    {
	    $this->namespace = $args[0];
	    $this->name      = $args[1];
        $this->file = file_get_contents(app_path('app/facades/http/controller'));
        $this->make();
        $this->put();
    }
    
    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Controller', $this->file);
        $this->file = str_replace('PATH', ucfirst($this->namespace), $this->file);
    }
    
    public function put()
    {
        if (file_put_contents(
            app_path('app/controllers/'.ucfirst($this->namespace).'/'.ucfirst($this->name).'Controller.php'),
            $this->file
        )) {
            Console::output(
                'Controller in path: App/Controllers/'.ucfirst($this->namespace).'/'.ucfirst($this->name).'Controller.php created.',
                'green'
            );
        }
    }
}
