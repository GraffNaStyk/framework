<?php

namespace App\Core;

use App\Facades\Http\Request;
use App\Facades\Http\Response;
use App\Facades\Http\Router\Router;
use App\Rules\RuleValidator;
use App\Facades\Validator\Validator;
use App\Helpers\Loader;
use App\Helpers\Session;
use App\Helpers\Storage;
use App\Facades\Http\View;
use App\Facades\Http\Router\Route;

abstract class BaseController
{
    public function __construct()
    {
        $this->boot();
    }
    
    public function boot()
    {
        Storage::private()->make('logs');
    
        $this->set([
            'messages' => Session::getMsg(),
            'color'    => Session::getColor(),
            'css'      => Loader::css(),
            'js'       => Loader::js()
        ]);
    
        $vars = Session::collectProvidedData();
        
        if ($vars) {
            View::set($vars);
        }
    
        Session::clearMsg();
    }

    public function redirect(?string $path, int $code=302, bool $direct=false): void
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
	
	public function render(array $data = [], $html = false)
	{
		return View::render($data, $html);
	}
    
    public function validate(array $request, string $rule, array $optional = []): bool
    {
        return Validator::make($request, RuleValidator::getRules($rule)->getRule($optional));
    }
    
    public function sendSuccess(string $message = null, string $to = null, bool $reload = true, int $status = 200, array $headers = []): ?string
    {
    	if (Request::isAjax()) {
		    Response::json([
		    	'ok'     => true,
			    'msg'    => $message ?? 'Dane zostały zapisane',
			    'to'     => $to,
			    'reload' => $reload
		    ],
			    $status,
			    $headers
		    );	
	    } else {
		    Session::msg($message);
		    return null;
	    }
    }
    
    public function sendError(string $message = null, int $status = 400, array $headers = []): ?string
    {
	    if (Request::isAjax()) {
		    Response::json([
			    'ok'     => false,
			    'msg'    => $message,
			    'inputs' => Validator::getErrors(),
			    'csrf'   => Session::get('@csrf.'.Router::csrfPath())
		    ],
			    $status,
			    $headers
		    );
	    } else {
		    Session::msg($message, 'danger');
		    return null;
	    }
    }
    
    public function response($response, $status=200): string
    {
        Response::json($response, $status);
    }
}
