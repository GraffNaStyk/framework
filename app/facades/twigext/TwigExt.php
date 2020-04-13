<?php namespace App\Facades\TwigExt;

use App\Core\Router;
use App\Core\Url;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Helpers\Session;

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
            $this->form_open(),
            $this->form_close(),
            $this->input(),
            $this->form_submit(),
            $this->csrf(),
            $this->img()
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

    public function form_open()
    {
        return new TwigFunction('form_open', function($url, $method = 'post', $enctype = false, $class = null) {
            $url = Url::get() . $url;
            $enctype = $enctype ? 'enctype="'.$enctype.'"' :'';
            echo '<form action="'.$url.'" method="'.$method.'"'.$enctype.' class="'.$class.'">';
        });
    }

    public function input()
    {
        return new TwigFunction('input', function($name, $class = null, $type = 'text') {
            echo '<input name="'.$name.'" class="'.$class.'" type="'.$type.'">';
        });
    }

    public function form_submit()
    {
        return new TwigFunction('form_submit', function($class = null, $value = 'Zapisz') {
            echo '<input class="'.$class.'" type="submit" value="'.$value.'">';
        });
    }

    public function img()
    {
        return new TwigFunction('img', function($url) {
            echo 'storage/'.$url;
        });
    }

    public function form_close()
    {
        return new TwigFunction('form_close', function() {
            echo '</form>';
        });
    }
}
