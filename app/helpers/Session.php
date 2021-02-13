<?php

namespace App\Helpers;

use App\Facades\Property\Get;
use App\Facades\Property\Has;

class Session
{
    public static function set($item, $data)
    {
	    $item = explode('.', $item);
	    
	    if (isset($item[3])) {
		    $_SESSION[$item[0]][$item[1]][$item[2]][$item[3]] = $data;
	    } else if (isset($item[2])) {
		    $_SESSION[$item[0]][$item[1]][$item[2]] = $data;
	    } else if (isset($item[1])) {
		    $_SESSION[$item[0]][$item[1]] = $data;
	    } else {
		    $_SESSION[$item[0]] = $data;
	    }
    }

    public static function get($item)
    {
        return Get::check($_SESSION, $item);
    }

    public static function all(): array
    {
        return $_SESSION;
    }

    public static function has($item)
    {
        return Has::check($_SESSION, $item);
    }

    public static function remove($item)
    {
	    $item = explode('.', $item);
	
	    if (isset($item[1])) {
		    unset($_SESSION[$item[0]][$item[1]]);
	    } else {
		    unset($_SESSION[$item[0]]);
	    }
    }

    public static function flash($item, $value = 1, $seconds = 60)
    {
        setcookie($item, $value, time()+$seconds, '/', getenv('SERVER_NAME'), true, true);
    }

    public static function getFlash($item)
    {
        return Get::check($_COOKIE, $item);
    }

    public static function hasFlash($item)
    {
        return Has::check($_COOKIE, $item);
    }

    public static function removeFlash($item)
    {
        unset($_COOKIE[$item]);
        setcookie($item, false, -1, '/', getenv('SERVER_NAME'), true, true);
    }
	
	public static function flashAll()
	{
		return $_COOKIE;
	}

    public static function msg($items, $color = 'success')
    {
        if (is_array($items)) {
            foreach ($items as $val)
                $_SESSION['msg'][] = $val;
        } else {
            $_SESSION['msg'][] = $items;
        }
        $_SESSION['color'] = $color;
    }

    public static function getMsg()
    {
        return isset($_SESSION['msg']) ? $_SESSION['msg'] : [];
    }

    public static function getColor()
    {
        return isset($_SESSION['color']) ? $_SESSION['color'] : '';
    }

    public static function clearMsg()
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
    
    public static function destroy()
    {
        session_destroy();
    }
}
