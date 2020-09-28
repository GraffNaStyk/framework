<?php namespace App\Facades\TwigExt;

use App\Facades\Http\View;
use App\Facades\Url\Url;
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
            $this->input(),
            $this->select(),
            $this->csrf(),
            $this->img(),
            $this->url(),
            $this->base(),
            $this->tooltip(),
            $this->component(),
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
            echo '<pre>';
            print_r($item);
            echo '</pre>';
        });
    }
    
    public function input()
    {
        return new TwigFunction('input', function($attr = []) {
            $html = '';
            if ($attr['type'] !== 'hidden') {
                if ($attr['type'] === 'file') {
                    $labelClass = 'class="input__upload"';
                }
                
                if(isset($attr['label'])) {
                    $html = '<label '.$labelClass.'><span>'.$attr['label'].'</span>';
                } else {
                    $html = '<label '.$labelClass.'>';
                }
            }
            
            unset($attr['label']);
            $inputText = '';
            
            foreach ($attr as $key => $value) {
                $inputText .= $key.'="'.$value.'"';
            }
    
            if ($attr['type'] === 'file') {
                $inputText .= 'style="display: none;"';
            }
            
            echo $html . '<input ' . $inputText. '/> </label>';
        });
    }
    
    public function select()
    {
        return new TwigFunction('select', function($attr = []) {
            if (isset($attr['label'])) {
                $html = '<label><span>'.$attr['label'].'</span>';
            } else {
                $html = '<label>';
            }
            
            unset($attr['label']);
            $selected = '';
            $select = '';
            $url = '';
            $data = '<option data-placeholder="true"></option>';
            $multiple = '';
            
            if (isset($attr['data']) && empty($attr['data']) === false) {
                foreach ($attr['data'] as $key => $value) {
                    if (isset($attr['selected'])) {
                        $selected = $value['value'] == $attr['selected'] ? 'selected' : '';
                    }
                    
                    $data .= '<option value="'.$value['value'].'" '.$selected.'>'.$value['text'].'</option>';
                }
                unset($attr['data']);
            }
            
            if (isset($attr['multiple'])) {
                $multiple = 'multiple';
            }
            
            if (isset($attr['url'])) {
                $url = 'data-url="'.$attr['url'].'"';
                unset($attr['url']);
            }
            
            $select .= '<select name="'.$attr['name'].'" data-select="slim" '.$url.' '.$multiple.'>';
            
            if($data !== '') {
                $select .= $data . '</select>';
            } else {
                $select .= '</select>';
            }
            
            echo $html . $select . '</label>';
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
    
    public function component()
    {
        return new TwigFunction('component', function($name, $variables = []) {
            View::setDirectly();
            View::change($name);
            View::render($variables);
        });
    }
}
