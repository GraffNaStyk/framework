<?php

namespace App\Core;

use App\Facades\Http\Response;
use App\Facades\Validator\Validator;
use App\Helpers\Loader;
use App\Helpers\Session;
use App\Helpers\Storage;
use App\Facades\Http\View;
use App\Facades\Http\Router;

abstract class Controller
{
    const PER_PAGE = 25;
    
    public function __construct()
    {
        $this->boot();
    }
    
    public function boot()
    {
        Storage::disk('private')->make('logs');
    
        $this->set([
            'messages' => Session::getMsg(),
            'color' => Session::getColor(),
            'css' => Loader::css(),
            'js' => Loader::js()
        ]);
    
        $vars = Session::collectProvidedData();
        if ($vars) {
            View::set($vars);
        }
    
        Session::clearMsg();
    }

    public function redirect(?string $path, int $code=302, bool $direct=false)
    {
        Router::redirect($path, $code, $direct);
    }
    
    public function set(array $data): void
    {
        View::set($data);
    }
    
    public function render(array $data = [])
    {
        if(empty($data) === false)
            return View::render($data);
        
        return View::render();
    }
    
    public function validate(array $request, array $rules)
    {
        return Validator::make($request, $rules);
    }
    
    public function sendSuccess(?string $message, string $to = null, int $status = 200 ,array $headers = []): string
    {
        return Response::json(['ok' => true, 'msg' => [$message ?? 'Dane zostały zapisane'], 'to' => $to], $status, $headers);
    }
    
    public function sendError(string $message=null, int $status = 400, array $headers = []): string
    {
        return Response::json(['ok' => false, 'msg' => $message ?? Validator::getErrors()], $status, $headers);
    }
}
