<?php

namespace App\Facades\Http;

use App\Facades\Config\Config;
use App\Facades\Http\Router\Router;
use App\Facades\TwigExt\TwigExt;
use Twig;

final class View
{
    protected static ?Twig\Environment $twig = null;

    private static array $data = [];

    protected static string $ext = '.twig';

    protected static string $layout = 'http';

    public static ?string $dir = null;

    public static ?string $view = null;

    public static function render(array $data = [])
    {
        self::set($data);
        self::register();
        self::$dir = Router::getAlias();
        
        if (Request::isAjax()) {
        	self::$layout = 'ajax';
        }
        
        self::set(['layout' => '/layouts/'.self::$layout.self::$ext]);
        self::set(['ajax' => '/layouts/ajax'.self::$ext]);
        self::setView();

        if (is_readable(view_path(self::$dir.'/'.Router::getClass().'/'.self::$view.self::$ext))) {
            return self::$twig->render(self::$dir.'/'.Router::getClass().'/'.self::$view.self::$ext, self::$data);
        }

        exit(require_once view_path('/errors/view-not-found.php'));
    }

    private static function setView(): void
    {
        self::$view = self::$view ?? Router::getAction();
        self::$view = strtolower(implode('-', preg_split('/(?=[A-Z])/', self::$view)));
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

    public static function getName(): string
    {
        return self::$view;
    }

    public static function registerFunctions(): void
    {
        foreach ((new TwigExt())->getFunctions() as $fn) {
            self::$twig->addFunction($fn);
        }
    }

    public static function mail(string $template, array $data = [])
    {
        self::register();
        self::set($data);
        return self::$twig->render('mail/'.$template.self::$ext, self::$data);
    }

    private static function register(): void
    {
        if (! self::$twig instanceof Twig\Environment) {
            if (Config::get('app.cache_view')) {
                $config['cache'] = storage_path('private/framework/views');
            }

            $config['debug'] = true;
            self::$twig = new Twig\Environment(new Twig\Loader\FilesystemLoader(view_path()), $config);
            self::$twig->addGlobal('isAjax', ((int) Request::isAjax() || Session::get('beAjax')));
            self::registerFunctions();
        }
    }
}
