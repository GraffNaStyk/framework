<?php

namespace App\Facades\Csrf;

use App\Facades\Config\Config;
use App\Facades\Helpers\Str;
use App\Facades\Http\Request;
use App\Facades\Http\Router\Router;
use App\Facades\Http\Session;

class Csrf
{

    public function isValid(string $csrf): bool
    {
        return (string) Session::get('@csrf.'.Router::csrfPath()) === $csrf;
    }

    public function valid(Request $request): bool
    {
        if (! $request->has('_csrf') && Config::get('app.csrf')) {
            return false;
        }

        $result = $this->isValid($request->get('_csrf'));

        $request->remove('_csrf');
        Session::remove('@csrf.'.Router::csrfPath());
        self::make(Router::csrfPath());

        return $result;
    }

    public static function make(string $uri): void
    {
    	session_regenerate_id();

        if (! Session::has('@csrf.'.$uri) && Config::get('app.csrf')) {
            Session::set('@csrf.'.$uri, session_id().Str::hash(30));
        }
    }
}
