<?php

namespace App\Facades\Http;

use App\Facades\Http\Router\Router;
use App\Facades\Property\Get;
use App\Facades\Property\Has;
use App\Facades\Property\Remove;
use App\Facades\Property\Set;
use App\Facades\Validator\Type;

class Session
{
    public static function set($item, $data): void
    {
        $_SESSION = array_merge($_SESSION, Set::set($_SESSION, Type::get($data), $item));
    }

    public static function get($item)
    {
        return Get::check($_SESSION, $item);
    }

    public static function all(): array
    {
        return $_SESSION;
    }

    public static function has($item): bool
    {
        return Has::check($_SESSION, $item);
    }

    public static function remove($item): void
    {
        $_SESSION = Remove::remove($_SESSION, $item);
    }

    public static function flash($item, $value = 1, $seconds = 60): void
    {
        setcookie($item, $value, time() + $seconds, '/', getenv('SERVER_NAME'), Router::checkProtocol() === 'https', true);
    }

    public static function getFlash($item): ?bool
    {
        return Get::check($_COOKIE, $item);
    }

    public static function hasFlash($item): bool
    {
        return Has::check($_COOKIE, $item);
    }

    public static function removeFlash($item): void
    {
        unset($_COOKIE[$item]);
        setcookie($item, false, - 1, '/', getenv('SERVER_NAME'), Router::checkProtocol() === 'https', true);
    }

    public static function flashAll(): array
    {
        return $_COOKIE;
    }

    public static function msg($items, $color = 'success'): void
    {
        if (is_array($items)) {
            foreach ($items as $val)
                $_SESSION['msg'][] = $val;
        } else {
            $_SESSION['msg'][] = $items;
        }

        $_SESSION['color'] = $color;
    }

    public static function getMsg(): array
    {
        return $_SESSION['msg'] ?: [];
    }

    public static function getColor(): ?string
    {
        return $_SESSION['color'] ?: '';
    }

    public static function clearMsg(): void
    {
        unset($_SESSION['msg']);
        unset($_SESSION['color']);
        unset($_SESSION['unused']);
    }

    public static function checkIfDataHasBeenProvided($request)
    {
        $_SESSION['unused'] = $request;
    }

    public static function collectProvidedData()
    {
        if (isset($_SESSION['unused'])) {
            return $_SESSION['unused'];
        }

        return null;
    }

    public static function destroy(): void
    {
        session_destroy();
    }
}
