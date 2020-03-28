<?php namespace App\Helpers;

use App\Core\Router;

class Loader
{
    public static function css()
    {
        $cssArr = glob(css_path('http/*.css'), GLOB_BRACE);

        $cssString = null;
        foreach ($cssArr as $key => $css)
            $cssString .= trim('<link rel="stylesheet" href="'.str_replace(app_path(), '', $css).'">'.PHP_EOL);

        $cssString .= static::getFileForView('css');

        $cssString .= self::getBootstrap('css');

        $cssString .= self::getFontAwesome();

        return $cssString;
    }

    public static function js()
    {
        $jsArr = glob(js_path('http/*.js'), GLOB_BRACE);
        $jsString = null;
        foreach ($jsArr as $key => $js)
            $jsString .= trim('<script type="module" src="'.str_replace(app_path(), '', $js).'"></script>'.PHP_EOL);

        $jsString .= static::getFileForView('js');

        $jsString .= self::getBootstrap('js');

        return $jsString;
    }

    public static function adminCss()
    {
        $cssArr = glob(css_path('admin/*.css'), GLOB_BRACE);
        $cssString = null;
        foreach ($cssArr as $key => $css)
            $cssString .= trim('<link rel="stylesheet" href="'.str_replace(app_path(), '', $css).'">'.PHP_EOL);

        $cssString .= static::getFileForView('css');

        $cssString .= self::getBootstrap('css');

        $cssString .= self::getFontAwesome();

        return $cssString;
    }

    public static function adminJs()
    {
        $jsArr = glob(js_path('admin/*.js'), GLOB_BRACE);
        $jsString = null;
        foreach ($jsArr as $key => $js)
            $jsString .= trim('<script type="text/javascript" src="'.str_replace(app_path(), '', $js).'"></script>'.PHP_EOL);

        $jsString .= static::getFileForView('js');

        $jsString .= self::getBootstrap('js');


        return $jsString;
    }

    private static function getBootstrap($ext)
    {
        $path = $ext == 'css'
            ? css_path('bootstrap.css')
            : js_path('bootstrap.js');

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
        $folder = Router::isAdmin() ? 'admin' : 'http';

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
