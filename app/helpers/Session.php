<?php namespace App\Helpers;

use App\Facades\Dotter\Get;
use App\Facades\Dotter\Has;

class Session
{
    public static function set($items)
    {
        foreach ($items as $key => $item)
            $_SESSION[$key] = $item;
    }

    public static function get($item)
    {
        $item = explode('.', $item);
        return Get::check($_SESSION, $item);
    }

    public static function all($key = null)
    {
        return $key ? $_SESSION[$key] : $_SESSION;
    }

    public static function has($item)
    {
        $item = explode('.', $item);
        return Has::check($_SESSION, $item);
    }

    public static function destroy()
    {
        return session_destroy();
    }

    public static function remove($item)
    {
        unset($_SESSION[$item]);
    }

    public static function flash($item, $value = 1, $seconds = 60)
    {
        setcookie($item, $value, time()+$seconds, '/', $_SERVER['SERVER_NAME']);
    }

    public static function getFlash($item = null)
    {
        if(!isset($_COOKIE[$item])) return false;
        if($item == null) return $_COOKIE;
        else return $_COOKIE[$item];
    }

    public static function hasFlash($item)
    {
        if(isset($_COOKIE[$item]) && !empty($_COOKIE[$item]))
            return true;

         return false;
    }

    public static function removeFlash($item)
    {
        unset($_COOKIE[$item]);
        setcookie($item, false, -1, '/', $_SERVER['SERVER_NAME']);
    }

    public static function msg($items, $color = 'success')
    {
        if(is_array($items)) {
            foreach ($items as $val)
                $_SESSION['msg'][] = $val;
        } else {
            $_SESSION['msg'][] = $items;
        }
        $_SESSION['color'] = $color;
    }

    public static function getMsg()
    {
        return isset($_SESSION['msg'])? $_SESSION['msg'] : [];
    }

    public static function getColor()
    {
        return isset($_SESSION['color'])? $_SESSION['color'] : '';
    }

    public static function clearMsg()
    {
        unset($_SESSION['msg']);
        unset($_SESSION['color']);
        unset($_SESSION['unused']);
    }

    public static function debug()
    {
        pd(['session-framework' => self::all(), 'session' => $_SESSION, 'cookie' => $_COOKIE]);
    }

    public static function checkIfDataHasBeenProvided($request)
    {
        $_SESSION['unused'] = $request;
    }

    public static function collectProvidedData()
    {

        if(isset($_SESSION['unused'])) {
            return $_SESSION['unused'];
        }
        return null;
    }
}
