<?php

namespace App\Facades\Console;

class Trigger
{
    use FileCreator;

    private string $file;
    private string $name;

    public function __construct($args = [])
    {
        $this->name = $args[0];
        $this->file = file_get_contents(app_path('app/facades/http/trigger'));
        $this->make();
    }

    public function make()
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Observer', $this->file);

        if (! is_dir(app_path('app/triggers/'))) {
            mkdir(app_path('app/triggers/'), 0775, true);
        }

        $this->putFile('app/triggers/'.ucfirst($this->name).'Observer.php', $this->file);
    }
}
