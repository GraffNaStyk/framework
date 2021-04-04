<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Facades\Http\Router\Route;
use App\Model\Client;
use App\Facades\Http\Request;

class ClientsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Request $request)
    {
        return $this->render([
            'users' => Client::select()->get()
        ]);
    }

    public function add()
    {
        return $this->render();
    }
    
    public function store(Request $request): string
    {
        if (! $this->validate($request->all(), 'client')) {
            return $this->sendError('Formularz nie został wysłany');
        }
        
        Client::insert($request->all())->exec();

        return $this->sendSuccess('Użytkownik dodany', '/clients', 201);
    }
    
    public function update(Request $request)
    {
    
    }
    
    public function show(int $id)
    {
    	$client = Client::select()->where('id', '=', $id)->exist();
    	
    	if ($client) {
		    return $this->render(['client' => $client]);
	    } else {
    		$this->redirect('/clients');
	    }
    }
    
    public function edit(int $id)
    {
    	return $this->render();
    }
    
    public function delete(int $id)
    {
    
    }
}
