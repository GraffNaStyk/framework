<?php

namespace App\Facades\Console;

class Controller
{
    use FileCreator;

    private string $file;
    private string $name;
    private bool $withView;
    private array $views = [
        'index.twig',
        'add.twig',
        'edit.twig',
        'show.twig'
    ];

    public function __construct($args = [])
    {
        $this->namespace = $args[0];
        $this->name = ucfirst($args[1]);
        $this->withView = (isset($args[2]) && $args[2] === '-v');
        $this->file = file_get_contents(app_path('app/facades/files/controller'));
        $this->make();
        $this->putFile(
            'app/controllers/'.$this->namespace.'/'.$this->name.'Controller.php',
            $this->file
        );
    }

    public function make()
    {
        $this->file = str_replace('CLASSNAME', $this->name.'Controller', $this->file);
        $this->file = str_replace('PATH', ucfirst($this->namespace), $this->file);

        if ($this->withView) {
            if (! is_dir(view_path(strtolower($this->namespace).'/'.$this->name))) {
                mkdir(view_path(strtolower($this->namespace).'/'.$this->name), 0775, true);
            }

            foreach ($this->views as $view) {
                if (! file_exists(view_path(strtolower($this->namespace).'/'.$this->name.'/'.$view))) {
                    file_put_contents(
                        view_path(strtolower($this->namespace).'/'.$this->name.'/'.$view),
                        file_get_contents(app_path('app/facades/files/view'))
                    );
                }
            }
        }
    }
}
