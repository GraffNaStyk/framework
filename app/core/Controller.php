<?php namespace App\Core;

use App\Facades\Validator\Validator;
use App\Helpers\Loader;
use App\Helpers\Session;
use App\Helpers\Storage;
use App\Facades\Http\View;
use App\Facades\Http\Router;
use App\Facades\Http\Response;

abstract class Controller
{
    public function __construct()
    {
        Storage::disk('private')->make('logs');

        View::set([
            'messages' => Session::getMsg(),
            'color' => Session::getColor(),
        ]);

        if($vars = Session::collectProvidedData())
            View::set($vars);

        Session::clearMsg();
        $this->resources();
    }

    public function resources(): void
    {
        View::set(['css' => Loader::css(), 'js' => Loader::js()]);
    }

    public function redirect($path, $code=302, $direct=false)
    {
        return Router::redirect($path, $code, $direct);
    }

    public function response($response, $status=200, $headers=[])
    {
        return Response::json($response, $status, $headers);
    }
    
    public function set(array $data):void
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
    
    public function errors()
    {
        return Validator::getErrors();
    }
    
    public function ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip;
    }
    
    public function action()
    {
        return Router::getAction();
    }
    
    public function class()
    {
        return Router::getClass();
    }
    
    public function params()
    {
        return Router::getParams();
    }
}
