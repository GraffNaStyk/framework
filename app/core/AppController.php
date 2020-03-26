<?php namespace App\Core;

use App\Helpers\Loader;
use App\Helpers\Session;

abstract class AppController
{
    public static $modelAlias = 'App\\Model\\';

    public function __construct()
    {
        if (app['admin'] && !Router::isAdmin())
            Router::redirect('admin');

        View::set([
            'url' => Url::get(),
            'base_url' => Url::base(),
            'messages' => Session::getMsg(),
            'color' => Session::getColor(),
        ]);

        if ($vars = Session::collectProvidedData())
            View::set($vars);

        Session::clearMsg();
    }

    public function is($item, $range = null) {
        if($range !== null) {
            if(isset($item[$range]) && !empty($item[$range]))
                return true;
            else return false;
        }
        if(isset($item) && !empty($item))
            return true;
        else return false;
    }

    public function loadForAdmin()
    {
        View::set(['css' => Loader::adminCss(), 'js' => Loader::adminJs()]);
    }

    public function loadForPage()
    {
        View::set(['css' => Loader::css(), 'js' => Loader::js()]);
    }
}
