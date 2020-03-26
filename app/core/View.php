<?php namespace App\Core;

use App\Facades\TwigExt\TwigExt;
use App\Helpers\Session;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;
use Twig_Error;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
require_once __DIR__ .'/../../vendor/autoload.php';

class View
{
    protected static $twig;
    public static $data = [];
    protected static $ext = '.twig';
    protected static $layout = 'page';
    public static $viewFolder = null;
    public static $view = null;

    public static function render(array $data = [])
    {
        self::set($data);

        $loader = new Twig_Loader_Filesystem(view_path());

        self::$twig = new Twig_Environment($loader, [
            'debug' => true,
        ]);

        self::$twig->addGlobal('session', $_SESSION);

        self::$twig->addExtension(new Twig_Extension_Debug());

        if(self::isAjax())
            self::setLayout('ajax');

        static::registerFunction();

        self::$viewFolder  = Router::isAdmin() ? 'Admin' : 'Http';

        self::$data['layout'] = 'Layouts/' . self::$layout . self::$ext;

        $view = self::$view == null ? Router::getAction() : self::$view ;

        self::$view = preg_split('/(?=[A-Z])/',$view);
        self::$view = strtolower(implode('_', self::$view ));

        if(!file_exists(view_path(self::$viewFolder . '/' . Router::getClass() . '/' . self::$view  . self::$ext)))
            Router::redirect('');

        try {
            return self::$twig->display(self::$viewFolder . '/' . Router::getClass() . '/' . self::$view  . self::$ext , self::$data);
        } catch (Twig_Error $e) {
            pd($e->getMessage());
        }
    }

    public static function isAjax(): bool
    {
        if (isset($_SERVER['HTTP_X_FETCH_HEADER']) && strtolower($_SERVER['HTTP_X_FETCH_HEADER']) == 'fetchapi')
            return true;

        return false;
    }


    public static function setLayout($layout): void
    {
        self::$layout = $layout;
    }

    public static function setView(string $view): void
    {
        self::$view = $view;
    }

    public static function set(array $data)
    {
        foreach ($data as $key => $value)
            self::$data[$key] = $value;
    }

    public static function registerFunction()
    {
        foreach (TwigExt::init()->getFunctions() as $fn) {
            self::$twig->addFunction($fn);
        }
    }
}
