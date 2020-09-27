<?php namespace App\Controllers\Admin;

use App\Controllers\ControllerInterface;
use App\Facades\Http\Request;
use App\Facades\Validator\Validator;
use App\Model\Client;

class ClientsController extends DashController implements ControllerInterface
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        echo 'test';
        exit;
    }

    public function add()
    {
        return $this->render();
    }
    
    public function store(Request $request)
    {
        pd($request->all());
        
        if(!$this->validate($request->all(), $this->validateRules()))
            return $this->response(['ok' => false, 'msg' => Validator::getErrors()], 400);
        
        Client::insert($request->all());

        return $this->response(['ok' => true, 'msg' => ['Użytkownik dodany']], 201);
    }
    
    public function update(Request $request)
    {
    
    }
    
    public function show(int $id)
    {
    
    }
    
    public function edit(int $id)
    {
    
    }
    
    public function delete(int $id)
    {
    
    }
    
    private function validateRules()
    {
        return [
            'name' => 'required|min:4',
            'ftp_server' => 'required|min:4',
            'ftp_user' => 'required|min:4',
            'ftp_password' => 'required|min:4',
            'db_user' => 'required|min:4',
            'db_password' => 'required|min:4',
            'db_name' => 'required|min:4',
        ];
    }
}
