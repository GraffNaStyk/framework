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
        $this->set(['page' => ['title' => 'Graff CRM']]);
    }
    
    private function setLayout()
    {
        View::layout(
            strtolower(Url::segment(Router::getInstance()->getCurrentRoute()->getNamespace(), 2, '\\'))
        );
    }
}
