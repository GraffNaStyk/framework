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
    protected static ?object $loader = null;

    public static function render(array $data = [])
    {
        self::set($data);
        
        if(self::$loader instanceof  Twig\Loader\FilesystemLoader === false) {
            self::$loader = new Twig\Loader\FilesystemLoader(view_path());
        }
    
        if(self::$twig instanceof  Twig\Environment === false) {
            $config['debug'] = true;
            if((bool) app['cache_view'] === true) {
                $config['cache'] = storage_path('framework/views');
            }
        
            self::$twig = new Twig\Environment(self::$loader, $config);
        
            self::$twig->addGlobal('session', $_SESSION);
            self::registerFunctions();
        }
        
        self::$dir = Router::getAlias() ?? 'http';

        self::set(['layout' => 'layouts/' . self::$layout . self::$ext]);
        self::setViewFile();
    
        if(self::$directly) {
            return self::$twig->display('/components/'.self::$view. self::$ext, self::$data);
        } else {
            if(file_exists(view_path(self::$dir . '/' . Router::getClass() . '/' . self::$view . self::$ext))) {
                return self::$twig->display(self::$dir . '/' . Router::getClass() . '/' . self::$view . self::$ext, self::$data);
            } else {
                exit(require_once view_path('errors/view-not-found.php'));
            }
        }
    }

    private static function setViewFile(): void
    {
        self::$view = self::$view ?? Router::getAction();
        $name = preg_split('/(?=[A-Z])/', self::$view);
        self::$view = strtolower(implode('-', $name));
    }

    public static function isAjax(): bool
    {
        if (isset($_SERVER['HTTP_X_FETCH_HEADER']) && (string) strtolower($_SERVER['HTTP_X_FETCH_HEADER']) === 'fetchapi')
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
    
    public static function getName()
    {
        return self::$view;
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
