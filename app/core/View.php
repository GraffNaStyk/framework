<?php namespace App\Core;

use App\Facades\TwigExt\TwigExt;
use Twig_Environment;
use Twig_Extension_Debug;
use Twig_Loader_Filesystem;
use Twig_Error;

require_once __DIR__ . '/../../vendor/autoload.php';

class View
{
    protected static $twig;
    public static $data = [];
    protected static $ext = '.twig';
    protected static $layout = 'page';
    public static $dir = null;
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

        if (self::isAjax())
            self::setLayout('ajax');

        self::registerFunction();

        self::$dir = Router::isAdmin() ? 'Admin' : 'Http';

        self::set(['layout' => 'Layouts/' . self::$layout . self::$ext]);

        self::$view = self::$view ?? Router::getAction();

        self::$view = preg_split('/(?=[A-Z])/', self::$view);
        self::$view = strtolower(implode('_', self::$view));

        try {
            return self::$twig->display(self::$dir . '/' . Router::getClass() . '/' . self::$view . self::$ext, self::$data);
        } catch (Twig_Error $e) {
            if (app['dev']) {
                pd($e->getMessage());
            } else {
                $date = date('Y-m-d H:i:s');
                file_put_contents(storage_path('private/logs/view_' . date('Y-m-d') . '.log'),
                    "[Date {$date}] {$e->getMessage()}" . PHP_EOL .
                    "---------------------------------------------" . PHP_EOL
                    , FILE_APPEND);
            }
        }
    }

    public static function isAjax(): bool
    {
        if (isset($_SERVER['HTTP_X_FETCH_HEADER']) && strtolower($_SERVER['HTTP_X_FETCH_HEADER']) == 'fetchapi')
            return true;

        return false;
    }


    public static function setLayout(string $layout): void
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
