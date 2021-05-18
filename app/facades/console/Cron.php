<?php

namespace App\Facades\Console;

class Cron
{
    protected string $path = '\\App\\Cron\\';
    private string $name;
    protected string $file;
    
    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/http/cron'));
        $this->make();
    }
    
    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Cron', $this->file);
        if (file_put_contents(
            app_path('app/cron/'.ucfirst($this->name).'Cron.php'),
            $this->file
        )) {
            Console::output(
                'Cronjob in path: app/cron/'.ucfirst($this->name).'Cron.php created.',
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
