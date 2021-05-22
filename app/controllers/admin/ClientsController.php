<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Facades\Http\Request;
use App\Helpers\Pagination;
use App\Model\Client;
use App\Rules\ClientValidator;

class ClientsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(int $page=1)
    {
    	Pagination::make(Client::class, $page, '/clients/page');

        return $this->render([
            'users' => Client::select()->paginate($page)->get()
        ]);
    }

    public function add()
    {
        return $this->render();
    }
    
    public function store(Request $request): string
    {
        if (! $this->validate($request->all(), ClientValidator::class)) {
            return $this->sendError('Formularz nie został wysłany');
        }

        Client::create($request->all());

        return $this->sendSuccess('Użytkownik dodany', [
        	'to' => '/clients'
        ]);
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
    
    public function delete(Request $request)
    {
        dd($request->all());
    }
}
