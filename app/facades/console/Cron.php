<?php

namespace App\Facades\Console;

class Cron
{
    use FileCreator;

    protected string $path = '\\App\\Cron\\';
    private string $name;
    protected string $file;

    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/files/cron'));

        if (isset($args[1])) {
            $this->run();
        } else {
            $this->make();
        }
    }

    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Cron', $this->file);
        $this->putFile('app/cron/'.ucfirst($this->name).'Cron.php', $this->file);
    }

    public function run()
    {
        $class = $this->path.$this->name.'Cron';
        (new $class());
    }
}
