<?php

namespace App\Facades\TwigExt;

use App\Facades\Http\Route;
use App\Facades\Http\Router;
use App\Facades\Url\Url;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Helpers\Session;

class TwigExt extends AbstractExtension
{
    public function getFunctions(): array
    {
        parent::getFunctions();
        return [
            $this->print(),
            $this->csrf(),
            $this->img(),
            $this->url(),
            $this->base(),
            $this->tooltip(),
        ];
    }

    public function csrf(): TwigFunction
    {
        return new TwigFunction('csrf', function() {
            echo Session::get('csrf');
        });
    }

    public function print(): TwigFunction
    {
        return new TwigFunction('print', function($item) {
            echo '<pre>';
            print_r($item);
            echo '</pre>';
        });
    }
    
    public function img(): TwigFunction
    {
        return new TwigFunction('img', function($url) {
            echo Route::checkProtocol().'://'.getenv('HTTP_HOST').Url::base().$url;
        });
    }

    public function url(): TwigFunction
    {
        return new TwigFunction('url', function($url = null) {
           if (Router::getAlias() === 'http') {
               echo Route::checkProtocol().'://'.getenv('HTTP_HOST').Url::base().$url;
           } else {
               echo Route::checkProtocol().'://'.getenv('HTTP_HOST').Url::base().'/'.Router::getAlias().$url;
           }
        });
    }

    public function base(): TwigFunction
    {
        return new TwigFunction('base', function($url = null) {
            echo Url::base().$url;
        });
    }

    public function tooltip(): TwigFunction
    {
        return new TwigFunction('tooltip', function($text, $placement = 'top') {
            echo 'data-toggle="tooltip" title="'.$text.'" data-placement="'.$placement.'"';
        });
    }
}
