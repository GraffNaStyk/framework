<?php

namespace App\Controllers;

use App\Facades\Config\Config;
use App\Facades\Helpers\CssLoader;
use App\Facades\Helpers\JavaScriptLoader;
use App\Facades\Http\AbstractController;
use App\Facades\Http\Router\Router;
use App\Facades\Http\View;
use App\Facades\Url\Url;

abstract class Controller extends AbstractController
{
	use JavaScriptLoader;
	use CssLoader;
	
    public function __construct()
    {
        parent::__construct();
        
        $this->setLayout();
	    $this->loadJsFromDir('/components');
	    $this->loadCssFromDir('/lib');
	    $this->loadCssFromDir('/admin');
	    $this->loadJs('/admin/admin.js');
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
