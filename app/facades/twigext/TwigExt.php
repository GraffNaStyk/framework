<?php namespace App\Facades\TwigExt;

use App\Facades\Url\Url,
    Twig\Extension\AbstractExtension,
    Twig\TwigFunction,
    App\Helpers\Session;

class TwigExt extends AbstractExtension
{
    public static function init()
    {
        return new self();
    }

    public function getFunctions()
    {
        return [
            $this->print(),
            $this->input(),
            $this->csrf(),
            $this->img(),
            $this->url(),
            $this->base(),
            $this->tooltip(),
        ];
    }

    public function csrf()
    {
        return new TwigFunction('csrf', function() {
            echo '<input type="hidden" name="csrf" value="'.Session::get('csrf').'">';
        });
    }

    public function print()
    {
        return new TwigFunction('print', function($item) {
            print_r($item);
        });
    }
    
    public function input()
    {
        return new TwigFunction('input', function($attr = []) {
            if(isset($attr['label'])) {
                $html = '<label><span>'.$attr['label'].'</span>';
            } else {
                $html = '<label>';
            }
            unset($attr['label']);
            $inputText = '';
            
            foreach ($attr as $key => $value)
                $inputText .= $key.'="'.$value.'"';
    
            echo $html . '<input ' . $inputText. '/> </label>';
        });
    }
    
    public function img()
    {
        return new TwigFunction('img', function($url) {
            echo 'storage/public/'.$url;
        });
    }

    public function url()
    {
        return new TwigFunction('url', function($url = null) {
            echo Url::get() . $url;
        });
    }

    public function base()
    {
        return new TwigFunction('base', function($url = null) {
            echo Url::base() . $url;
        });
    }

    public function tooltip()
    {
        return new TwigFunction('tooltip', function($text, $placement = 'top') {
            echo 'data-toggle="tooltip" title="'.$text.'" data-placement="'.$placement.'"';
        });
    }
}
