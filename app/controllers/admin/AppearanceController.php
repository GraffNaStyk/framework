<?php namespace App\Controllers\Admin;

use App\Facades\Http\Request;
use App\Facades\Validator\Validator;
use App\Model\Config;

class AppearanceController extends DashController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function colors()
    {
       return $this->render(
           Config::where(['id', '=', 1])->findOrFail()
       );
    }

    public function store(Request $request)
    {
       if(!$this->validate($request->all(), [
            'bgcolor' => 'required',
            'footercolor' => 'required'
        ])) return $this->response(['ok' => false, 'msg' => Validator::getErrors()], 400);

       if(Config::where(['id', '=', 1])->update($request->all()))
           return $this->response(['ok' => true, 'msg' => ['Dane zostały zapisane']]);
       
        return $this->response(['ok' => false, 'msg' => ['Wystąpił błąd']], 400);
    }

    public function seo()
    {
        return $this->render(
            Config::select(['description', 'title', 'keywords'])->where(['id', '=', 1])->findOrFail()
        );
    }
}
