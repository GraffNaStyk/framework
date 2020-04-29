<?php namespace App\Controllers\Http;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Facades\Validator\Validator;
use App\Model\Player;

class ContactController extends IndexController
{
    public function __construct()
    {
        parent::__construct();
        View::set(['title' => 'Graff Design - kontakt']);
    }

    public function index()
    {
        return View::render();
    }

    public function send(Request $request)
    {
       if(!Validator::make($request->all(), [
           'name' => 'required',
           'email' => 'required|email',
           'text' => 'required'
       ])) return Response::json(['ok' => false, 'msg' =>'Formularz uzupełniony nieprawidłowo', 'class' => 'danger'], 200);

        mail(app['mail']['to'], "Zapytanie ze strony - {$request->get('email')}", "name: {$request->get('name')} <br>email: {$request->get('email')} <br>text: {$request->get('text')}");

        return Response::json(['ok' => true, 'msg' =>'Dziękuję za kontakt!', 'class' => 'success'], 200);
    }
}
