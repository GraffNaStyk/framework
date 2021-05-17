<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Core\App;
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
    	Pagination::make(Client::class, $page, '/clients');
        return $this->render([
            'users' => Client::select()->limit(App::PER_PAGE)->offset(($page-1)*App::PER_PAGE)->get()
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

        Client::insert($request->all())->exec();

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
    
    public function delete(int $id)
    {
    
    }
}
