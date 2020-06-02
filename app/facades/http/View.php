<?php namespace App\Facades\Http;

use App\Facades\TwigExt\TwigExt;
use Twig;

require_once vendor_path('autoload.php');

final class View
{
    protected static $twig;
    private static array $data     = [];
    protected static string $ext    = '.twig';
    protected static string $layout = 'page';
    public static ?string $dir = null;
    public static ?string $view = null;
    public static bool $directly = false;
    protected static $loader;

    public static function render(array $data = [])
    {
        self::set($data);
        
        if(self::$loader instanceof  Twig\Loader\FilesystemLoader === false) {
            self::$loader = new Twig\Loader\FilesystemLoader(view_path());
        }
        
        if(self::$twig instanceof  Twig\Environment === false) {
            self::$twig = new Twig\Environment(self::$loader, [
                'debug' => true,
                'cache' => storage_path('framework/views'),
            ]);
        
            self::$twig->addGlobal('session', $_SESSION);
            self::registerFunctions();
        }
        
        self::$dir = Router::getAlias() ?? 'http';

        self::set(['layout' => 'layouts/' . self::$layout . self::$ext]);
        self::setViewFile();

        try {
            if(self::$directly) {
                return self::$twig->display('/components/'.self::$view. self::$ext, self::$data);
            } else {
                return self::$twig->display(self::$dir . '/' . Router::getClass() . '/' . self::$view . self::$ext, self::$data);
            }
        } catch (Twig\Error\Error $e) {
            if (app['dev']) {
                pd($e->getMessage());
            } else {
                $date = date('Y-m-d H:i:s');
                file_put_contents(storage_path('private/logs/view_' . date('d-m-Y') . '.log'),
                    "[Date {$date}] {$e->getMessage()}" . PHP_EOL .
                    "---------------------------------------------" . PHP_EOL
                    , FILE_APPEND);
            }
        }
        return false;
    }

    private static function setViewFile(): void
    {
        self::$view = self::$view ?? Router::getAction();
        $name = preg_split('/(?=[A-Z])/', self::$view);
        self::$view = strtolower(implode('-', $name));
    }

    public static function isAjax(): bool
    {
        if (isset($_SERVER['HTTP_X_FETCH_HEADER']) && strtolower($_SERVER['HTTP_X_FETCH_HEADER']) == 'fetchapi')
            return true;

        return false;
    }

    public static function layout(string $layout): void
    {
        self::$layout = $layout;
    }

    public static function change(string $view): void
    {
        self::$view = $view;
    }

    public static function set(array $data): void
    {
        foreach ($data as $key => $value) {
            self::$data[$key] = $value;
        }
    }
    
    public static function getData()
    {
        return self::$data;
    }
    
    public static function setDirectly()
    {
        self::$directly = true;
    }

    public static function registerFunctions(): void
    {
        foreach (TwigExt::init()->getFunctions() as $fn) {
            self::$twig->addFunction($fn);
        }
    }
}
