<?php namespace App\Core;

use App\Helpers\Loader;
use App\Helpers\Session;
use App\Helpers\Storage;
use App\Facades\Url\Url;

abstract class AppController
{
    public static $modelAlias = 'App\\Model\\';

    public function __construct()
    {
        Storage::disk('private')->make('logs');

        View::set([
            'url' => Url::get(),
            'base_url' => Url::base(),
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
}
