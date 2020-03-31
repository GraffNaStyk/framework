<?php namespace App\Controllers\Http;

use App\Core\Request;
use App\Core\Router;
use App\Core\View;
use App\Helpers\Storage;

class ExampleController extends IndexController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function form()
    {
         return View::render([
            'img' =>  Storage::disk('public')->get('/', '*')
        ]);
    }

    public function upload(Request $request)
    {
        Storage::disk('public')->upload($request->file('asda'), '/', 'eluwinka.png');
        Router::redirect('Example/form');
    }
}
