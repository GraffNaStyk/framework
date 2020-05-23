<?php namespace App\Controllers\Admin;

use App\Facades\Http\Request;
use App\Facades\Http\View;
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
           Config::where(['id', '=', 1])->findOrFail()
       );
    }

    public function store(Request $request)
    {
        if(Config::where(['id', '=', 1])->update($request->all()))
            return $this->response(['ok' => true, 'msg' => ['Dane zostały zapisane']]);

        return $this->response(['msg' => ['Wystąpił błąd'], 'ok' => false]);
    }

    public function seo()
    {
        return View::render([
            'config' => Config::select(['description', 'title', 'keywords'])->where(['id', '=', 1])->findOrFail()
        ]);
    }
}
