<?php namespace App\Core;

use App\Helpers\Loader;
use App\Helpers\Session;
use App\Helpers\Storage;

abstract class AppController
{
    public static $modelAlias = 'App\\Model\\';

    public function __construct()
    {
        Storage::disk('private')->make('logs');

        if (app['admin'] && !Router::isAdmin())
            Router::redirect(app['cms']);

        View::set([
            'url' => Url::get(),
            'base_url' => Url::base(),
            'messages' => Session::getMsg(),
            'color' => Session::getColor(),
        ]);

        if($vars = Session::collectProvidedData())
            View::set($vars);

        Session::clearMsg();
    }

    public function loadForAdmin(): void
    {
        View::set(['css' => Loader::adminCss(), 'js' => Loader::adminJs()]);
    }

    public function loadForPage(): void
    {
        View::set(['css' => Loader::css(), 'js' => Loader::js()]);
    }
}
