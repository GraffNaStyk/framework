<?php

namespace App\Facades\Console;

class Cronjob
{
    protected string $path = '\\App\\Cronjobs\\';
    private string $name;
    protected string $file;
    
    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/http/cronjob'));
    }
    
    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Cronjob', $this->file);
        if (file_put_contents(
            app_path('app/cronjobs/'.ucfirst($this->name).'Cronjob.php'),
            $this->file
        )) {
            Console::output(
                'Cronjob in path: app/cronjobs/'.ucfirst($this->name).'Cronjob.php created.',
                'green'
            );
        }
    }
    
    public function run()
    {
        $class = $this->path.$this->name.'Cronjob';
        (new $class());
    }
}
