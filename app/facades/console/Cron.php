<?php

namespace App\Facades\Console;

use App\Facades\Dependency\Container;
use App\Facades\Dependency\ContainerBuilder;
use ReflectionClass;

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

    public function make(): void
    {
        $this->file = str_replace('CLASSNAME', ucfirst($this->name).'Cron', $this->file);
        $this->putFile('app/cron/'.ucfirst($this->name).'Cron.php', $this->file);
    }

    public function run(): void
    {
        $class     = $this->path.$this->name.'Cron';
        $reflector = new ReflectionClass($class);

        if ($reflector->hasMethod('__construct')) {
	        $container = new ContainerBuilder(new Container());
	        call_user_func_array([$reflector, 'newInstance'],
		        $container->reflectConstructorParams($reflector->getConstructor()->getParameters())
	        );
        } else {
	        (new $class());
        }
    }
}
