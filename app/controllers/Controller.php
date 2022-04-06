<?php

namespace App\Controllers;

use App\Facades\Config\Config;
use App\Facades\Helpers\CssLoader;
use App\Facades\Helpers\JavaScriptLoader;
use App\Facades\Http\AbstractController;
use App\Facades\Http\View;
use App\Facades\Url\Url;

abstract class Controller extends AbstractController
{
	use JavaScriptLoader;
	use CssLoader;
	
    public function __construct()
    {
        parent::__construct();

	    $this->loadJs('/app');
        $this->setLayout();
	    $this->loadJsFromDir('/components');
	    $this->loadJsFromDir('/lib');
	    $this->loadCssFromDir('/lib');
	    $this->enableCssAutoload();
	    $this->enableJsAutoload();
    }

    private function setLayout(): void
    {
        $layout = strtolower(
            Url::segment($this->routeParams('namespace'), 2, '\\')
        );

        View::layout($layout);

        if ($layout === 'admin') {
            $this->setData(['menu' => Config::get('menu')]);
	        $this->loadCssFromDir('/admin/components');
        } else {
	        $this->loadCssFromDir('/http');
	        $this->loadJs('/http/http');
        }
    }
}
