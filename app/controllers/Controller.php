<?php

namespace App\Controllers;

use App\Core\AbstractController;
use App\Facades\Http\Router\Router;
use App\Facades\Http\View;
use App\Facades\Url\Url;

abstract class Controller extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
        $this->setLayout();
    }

    private function setLayout(): void
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
