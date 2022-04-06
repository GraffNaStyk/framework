<?php

namespace App\Controllers\Middleware;

use App\Controllers\UserState;
use App\Facades\Http\Request;
use App\Facades\Http\Router\Collection;
use App\Facades\Http\Router\Route;
use App\Facades\Http\Router\RouteGenerator;
use App\Facades\Http\Router\Router;
use App\Facades\Http\Session;
use App\Facades\Log\Log;
use App\Facades\Url\Url;
use App\Models\User;

final class Auth
{
    private static array $methods = [
        1 => ['index', 'show'],
        2 => ['add', 'edit', 'store', 'update', 'upload'],
        3 => ['delete']
    ];

    public function before(Request $request, Router $router): void
    {
        $user = User::select(['id'])->where('id', '=', UserState::id())->exist();

        if (! Session::has('user') || ! $user) {
            if (Request::isAjax()) {
                Router::abort(401);
            } else {
                Route::redirect(RouteGenerator::generate('Login@index'));
            }
        }

        if (! $this->checkRights($router->getCurrentRoute())) {
            Log::custom('unauthorized', [
                'message' => 'Unauthorized access',
                'user' => UserState::user()
            ]);

            if (Request::isAjax()) {
                Router::abort(401);
            } else {
	            Route::redirect(Url::fullWithAlias());
            }
        }
    }

    public function checkRights(Collection $route): bool
    {
        if ($route->getRights() === 0) {
            return false;
        }

        if ($route->getRights() === 4) {
            return true;
        }

        if (class_exists(Right::class)) {
            $fields     = array_column(Right::getColumnsInfo(), 'field');
            $controller = strtolower($route->getController());

            if (! in_array($controller, $fields, true)) {
                Log::custom('rightColumnNotExist', [
                    'column' => strtolower($route->getController())
                ]);

                return false;
            }

            $result = Right::select([$controller])
                ->where('user_id', '=', UserState::id())
                ->first();

            if (empty($result) || $result->{$controller} < $route->getRights()) {
                return false;
            }

            $methods = self::$methods[1];

            if ($route->getRights() === 2) {
                $methods = [...self::$methods[1], ...self::$methods[2]];
            }

            if ($route->getRights() === 3) {
                $methods = [...self::$methods[1], ...self::$methods[2], ...self::$methods[3]];
            }

            if (! in_array($route->getAction(), $methods, true)) {
                return false;
            }
        }

        return true;
    }
}
