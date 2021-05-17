<?php

namespace App\Controllers;

use App\Core\BaseController;
use App\Facades\Http\Router\Router;
use App\Facades\Http\View;
use App\Facades\Url\Url;

class Controller extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->setLayout();
    }
    
    private function setLayout()
    {
    	$layout = strtolower(
    		Url::segment(Router::getInstance()->getCurrentRoute()->getNamespace(), 2, '\\')
	    );
        View::layout($layout);
        
        if ($layout === 'admin') {
        	$this->set(['menu' => config('menu')]);
        }
    }
}
