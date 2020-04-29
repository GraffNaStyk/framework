<?php namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Core\View;
use App\Facades\Validator\Validator;
use App\Helpers\Session;
use App\Model\Client;

class ClientsController extends DashController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return View::render([
            'clients' => Client::select(['name', 'www','id'])->get()
        ]);
    }

    public function create()
    {
        return View::render();
    }

    public function save(Request $request)
    {
        if(! Validator::make($request->all(), [
            'name' => 'required',
            'ftp_server' => 'required',
            'ftp_user' => 'required',
            'ftp_password' => 'required',
            'db_user' => 'required',
            'db_password' => 'required',
            'www' => 'required',
            'db_name' => 'required',
        ])) Router::redirect('clients/create');

        if(Client::insert($request->all()))
            Session::msg('Client save success');

        Router::redirect('clients');
    }

    public function update(Request $request)
    {
        if(Client::update($request->all()))
            Session::msg('client has been update');
        else
            Session::msg('Failed to save client', 'danger');

        Router::redirect("clients/show/{$request->get('id')}");
    }

    public function show($id)
    {
        View::change('create');

        if($client = Client::where(['id', '=', $id])->findOrFail())
            return View::render($client);

        Session::msg('Client not exist!', 'danger');
        Router::redirect('Clients');
    }

    public function delete($id)
    {
        if(Client::where(['id', '=', $id])->delete())
            Session::msg('Client delete success!');

        Router::redirect('clients');
    }
}
