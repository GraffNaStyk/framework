<?php

namespace App\Facades\TwigExt;

use App\Facades\Http\Router;
use App\Facades\Http\View;
use App\Facades\Url\Url;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Helpers\Session;

class TwigExt extends AbstractExtension
{
    public static function init(): TwigExt
    {
        return new self();
    }

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
            echo 'storage/public/'.$url;
        });
    }

    public function url(): TwigFunction
    {
        return new TwigFunction('url', function($url = null) {
           if (Router::getAlias() === 'http') {
               echo Url::base().$url;
           } else {
               echo Url::base().'/'.Router::getAlias().$url;
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
