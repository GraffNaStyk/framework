<?php namespace App\Controllers\Admin;

use App\Facades\Http\Request;
use App\Facades\Http\View;
use App\Db\Model;
use App\Model\Config;

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
        if(Config::where(['id', '=', 1])->update($request->all()))
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
