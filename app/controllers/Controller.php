<?php

namespace App\Controllers;

use App\Facades\Config\Config;
use App\Facades\Http\AbstractController;
use App\Facades\Http\Router\Router;
use App\Facades\Http\View;
use App\Facades\Url\Url;
use App\Helpers\Loader;

abstract class Controller extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
        $this->setLayout();
	
        Loader::set();

	    $this->setData([
		    'css' => Loader::css(),
		    'js'  => Loader::js(),
	    ]);
    }

    private function setLayout(): void
    {
        $layout = strtolower(
            Url::segment(Router::getInstance()->getCurrentRoute()->getNamespace(), 2, '\\')
        );

        View::layout($layout);

        if ($layout === 'admin') {
            $this->setData(['menu' => Config::get('menu')]);
        }
    }
}
