<?php namespace App\Core;

use App\Helpers\Loader;
use App\Helpers\Session;
use App\Helpers\Storage;

abstract class AppController
{
    public function __construct()
    {
        Storage::disk('private')->make('logs');

        View::set([
            'messages' => Session::getMsg(),
            'color' => Session::getColor(),
        ]);

        if($vars = Session::collectProvidedData())
            View::set($vars);

        Session::clearMsg();
        $this->resources();
    }

    public function resources(): void
    {
        View::set(['css' => Loader::css(), 'js' => Loader::js()]);
    }

    public function redirect($path, $code, $direct)
    {
        Router::redirect($path, $code, $direct);
    }
}
