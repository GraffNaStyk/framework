<?php namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Db\Model;

class AppearanceController extends DashController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function colors()
    {
       return View::render(
           Model::table('config')->where(['id', '=', 1])->findOrFail()
       );
    }

    public function store(Request $request)
    {
        $request->set('id', 1);

        if(Model::table('config')->update($request->all()))
            return Response::json(['class' => 'success', 'msg' => 'Kolory zaktualizowane pomyślnie']);

        return Response::json(['class' => 'danger', 'msg' => 'Wystąpił błąd']);
    }

    public function seo()
    {
        return View::render(
            Model::table('config')->where(['id', '=', 1])->findOrFail()
        );
    }
}
