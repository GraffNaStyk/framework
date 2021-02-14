<?php

namespace App\Facades\Console;

class Controller
{
    private string $file;
    private string $name;
    
    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/http/controller'));
    }
    
    public function http()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Controller', $this->file);
        $this->file = str_replace('PATH', 'Http', $this->file);
        $this->put('http');
    }
    
    public function admin()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Controller', $this->file);
        $this->file = str_replace('PATH', 'Admin', $this->file);
        $this->put('admin');
    }
    
    public function put($where)
    {
        if (file_put_contents(
            app_path('app/controllers/'.$where.'/'.ucfirst($this->name).'Controller.php'),
            $this->file
        )) {
            Console::output(
                'Controller in path: app/controllers/'.$where.'/'.ucfirst($this->name).'Controller.php created.',
                'green'
            );
        }
    }
}
