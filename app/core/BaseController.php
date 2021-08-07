<?php

namespace App\Core;

use App\Facades\Http\Request;
use App\Facades\Http\Response;
use App\Facades\Http\Router\Route;
use App\Facades\Http\Router\Router;
use App\Facades\Http\Session;
use App\Facades\Http\View;
use App\Facades\Storage\Storage;
use App\Facades\Validator\Validator;
use App\Helpers\Loader;

abstract class BaseController
{
    public function __construct()
    {
        $this->boot();
    }

    public function boot(): void
    {
        Storage::private()->make('logs')->make('cache');

        $this->set([
            'css' => Loader::css(),
            'js' => Loader::js(),
        ]);

        Session::remove('beAjax');
        Session::clearMsg();
    }

    public function redirect(?string $path, int $code = 302, bool $direct = false): void
    {
        Route::redirect($path, $code, $direct);
    }

    public function redirectWhen(string $when, string $then): void
    {
        Route::when($when, $then);
    }

    public function set(array $data): void
    {
        View::set($data);
    }

    public function render(array $data = []): string
    {
        return View::render($data);
    }
	
	public function validate(array $request, string $rule, array $optional = []): bool
	{
		$result = Validator::validate($request, (new $rule())->getRule($optional));
		
		if (method_exists($rule, 'beforeValidate') && ! $result) {
			Validator::setErrors($rule::beforeValidate(Validator::getErrors()));
		}
		
		return $result;
	}

    public function sendSuccess(string $message = null, array $params = [], int $status = 200): ?string
    {
        if (Request::isAjax() || (API && defined('API'))) {
            Session::set('beAjax', true);
            return Response::json([
                'ok' => true,
                'msg' => $message ?: 'Dane zostały zapisane',
                'params' => $params,
            ],
                $status,
                []
            );
        } else {
            Session::msg($message);
            return null;
        }
    }

    public function sendError(string $message = null, array $params = [], int $status = 400): ?string
    {
        if (Request::isAjax() || (API && defined('API'))) {
            Session::set('beAjax', true);
            return Response::json([
                'ok' => false,
	            'msg' => $message ?: 'Wystąpił błąd',
                'inputs' => Validator::getErrors(),
                'csrf' => Session::get('@csrf.'.Router::csrfPath()),
                'params' => $params,
            ],
                $status,
                []
            );
        } else {
            Session::msg($message, 'danger');
            return null;
        }
    }

    public function response(array $response = [], int $status = 200, array $headers = []): string
    {
        return Response::json($response, $status, $headers);
    }
}
