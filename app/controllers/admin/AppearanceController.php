<?php namespace App\Controllers\Admin;

use App\Core\Request;
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
            return $this->response(['ok' => true, 'msg' => ['Kolory zaktualizowane pomyślnie']]);

        return $this->response(['msg' => ['Wystąpił błąd'], 'ok' => false]);
    }

    public function seo()
    {
        return View::render(
            Model::table('config')->where(['id', '=', 1])->findOrFail()
        );
    }
}
