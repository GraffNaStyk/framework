<?php

namespace App\Controllers\Admin;

use App\Facades\Http\Request;
use App\Facades\Rules\RuleValidator;
use App\Model\User;


class ClientsController extends DashController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        return $this->render([
            'users' => User::select()->get()
        ]);
    }

    public function add()
    {
        return $this->render();
    }
    
    public function store(Request $request): string
    {
        if (! $this->validate($request->all(), 'client')) {
            return $this->sendError();
        }
        
        Client::insert($request->all())->exec();

        return $this->sendSuccess('Użytkownik dodany', '', 201);
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
    
    public function password()
    {
        return $this->render();
    }
}
