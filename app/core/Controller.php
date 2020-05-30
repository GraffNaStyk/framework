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
        return View::render($data);
    }
    
    public function validate(array $request, array $rules)
    {
        return Validator::make($request, $rules);
    }
}
