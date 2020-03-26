<?php namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Router;
use App\Facades\Validator\Validator;

class SeoController extends DashController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        parent::index();
    }

    public function save(Request $request)
    {
        if(! Validator::make($request->all(), [
            'test' => 'required|min:10',
            'dupa' => 'required|min:10'
        ]))
            Router::redirect('Seo');
    }

    public function edit($id)
    {

    }

    public function delete($id)
    {

    }
}
