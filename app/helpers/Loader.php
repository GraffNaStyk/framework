<?php namespace App\Helpers;

use App\Facades\Http\Router;

class Loader
{
    protected static array $loaded = [
        'css' => ['bootstrap', 'slim-select'],
        'js' => ['bootstrap', 'slim-select'],
    ];
    
    public static function css()
    {
        $folder = Router::getAlias() ?? 'http';

        $cssArr = glob(css_path("$folder/*.css"), GLOB_BRACE);

        $cssString = null;
        foreach ($cssArr as $key => $css)
            $cssString .= trim('<link rel="stylesheet" href="'.str_replace(app_path(), '', $css).'">'.PHP_EOL);

        $cssString .= static::getFileForView('css');
        
        foreach (self::$loaded['css'] as $val)
            $cssString .= self::getFile($val, 'css');

        $cssString .= self::getFontAwesome();
 
        return $cssString;
    }

    public static function js()
    {
        $folder = Router::getAlias() ?? 'http';

        $jsArr = glob(js_path("$folder/*.js"), GLOB_BRACE);

        $jsString = null;
        foreach ($jsArr as $key => $js)
            $jsString .= trim('<script type="module" src="'.str_replace(app_path(), '', $js).'"></script>'.PHP_EOL);

        $jsString .= static::getFileForView('js');
    
        foreach (self::$loaded['js'] as $val)
            $jsString .= self::getFile($val, 'js');

        return $jsString;
    }

    private static function getFile($name, $ext)
    {
        $path = $ext == 'css'
            ? css_path($name.'.css')
            : js_path($name.'.js');

        if(is_file($path)) {
            if($ext == 'css')
                return trim('<link rel="stylesheet" href="'.str_replace(app_path(), '', $path).'">'.PHP_EOL);

             return trim('<script src="'.str_replace(app_path(), '', $path).'"></script>'.PHP_EOL);
        }
        return '';
    }

    private static function getFontAwesome()
    {
        return is_file(css_path('font-awesome.min.css'))
            ? trim('<link rel="stylesheet" href="'.str_replace(app_path(), '', css_path('font-awesome.min.css')).'">'.PHP_EOL)
            : null;
    }

    private static function getFileForView($ext)
    {
        $folder = Router::getAlias() ?? 'http';

        $path = $ext == 'css'
            ? css_path($folder.'/'.Router::getClass().'/'.Router::getAction().'.css')
            : js_path($folder.'/'.Router::getClass().'/'.Router::getAction().'.js');

        if(is_file($path)) {
            if($ext == 'css')
                return trim('<link rel="stylesheet" href="'.str_replace(app_path(), '', $path).'">'.PHP_EOL);
            else return trim('<script type="module" src="'.str_replace(app_path(), '', $path).'"></script>'.PHP_EOL);
        }
        return '';
    }
}
