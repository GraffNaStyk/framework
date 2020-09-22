<?php namespace App\Controllers\Admin;

use App\Facades\Http\Request;
use App\Facades\Validator\Validator;
use App\Model\Client;

class ClientsController extends DashController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->render();
    }
    
    public function add(?int $id = null)
    {
        echo 'eluwina';
    }
    
    public function store(Request $request)
    {
        if(!$this->validate($request->all(), [
            'name' => 'required|min:4',
            'ftp_server' => 'required|min:4',
            'ftp_user' => 'required|min:4',
            'ftp_password' => 'required|min:4',
            'db_user' => 'required|min:4',
            'db_password' => 'required|min:4',
            'db_name' => 'required|min:4',
        ])) return $this->response(['ok' => false, 'msg' => Validator::getErrors()], 400);
        
        Client::insert($request->all());

        return $this->response(['ok' => true, 'msg' => ['Użytkownik dodany']], 201);
    }
}
