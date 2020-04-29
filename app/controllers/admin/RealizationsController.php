<?php namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Router;
use App\Core\View;
use App\Facades\Validator\Validator;
use App\Helpers\Session;
use App\Helpers\Storage;
use App\Model\Realization;
use App\Core\Response;

class RealizationsController extends DashController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return View::render([
            'realizations' => Realization::select(['id', 'title', 'page_url'])->get()
        ]);
    }

    public function get()
    {
        return Response::json(Realization::select(['title', 'page_url'])->get());
    }

    public function create()
    {
        return View::render();
    }

    public function save(Request $request)
    {
        if(! Validator::make($request->all(), [
            'title' => 'required',
            'page_url' => 'required',
            'description' => 'required',
        ])) Router::redirect('Realizations/create');

        if(Realization::insert($request->all())) {
            $id = Realization::lastId();
            Storage::disk('public')
                ->upload($request->file('image'), "/$id/", "$id.jpg");

            Session::msg('Realization save success');
        }

        Router::redirect('Realizations');
    }

    public function edit($id)
    {
        View::setView('create');
        if($realization = Realization::where(['id', '=', $id])->findOrFail())
            return View::render($realization);

        Session::msg('Realization not exist!', 'danger');
        Router::redirect('Realizations');
    }

    public function delete($id)
    {
        if(Realization::where(['id', '=', $id])->delete())
            Session::msg('Realization delete success!');

        Router::redirect('realizations');
    }
}
