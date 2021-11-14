<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Facades\Faker\Faker;
use App\Facades\Http\Request;
use App\Facades\Http\Response;
use App\Helpers\Pagination;
use App\Models\Client;
use App\Rules\ClientValidator;

class ClientsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request, Client $client, int $page = 1): Response
    {
    	$client->insert([
    		'name'         => Faker::string(4),
    		'www'          => Faker::string(4),
    		'ftp_server'   => Faker::string(4),
    		'ftp_user'     => Faker::string(4),
    		'ftp_password' => Faker::string(4),
    		'db_link'      => Faker::string(4),
    		'db_user'      => Faker::string(4),
    		'db_password'  => Faker::string(4),
    		'db_name'      => Faker::string(4),
	    ])->exec();

        Pagination::make(Client::class, $page, '/clients/page');

        return $this->render([
            'clients' => $client->select()->paginate($page)->get()
        ]);
    }

    public function add(): Response
    {
        return $this->render();
    }

    public function store(Request $request): Response
    {
        if (! $this->validate($request->all(), ClientValidator::class)) {
            return $this->sendError('Formularz nie został wysłany');
        }
        
        Client::create($request->all());

        return $this->sendSuccess('Użytkownik dodany', [
            'to' => '/clients',
        ]);
    }

    public function update(Request $request): Response
    {

    }

    public function show(int $id): ?Response
    {
        $client = Client::select()->where('id', '=', $id)->exist();

        if ($client) {
        	dump($client);
            return $this->render(['client' => $client]);
        } else {
            return $this->redirect('/clients');
        }
    }

    public function edit(int $id): Response
    {
        return $this->render();
    }

    public function delete(Request $request): Response
    {
        Client::delete()->where('id', '=', $request->get('id'))->exec();

        return $this->sendSuccess('Usunięto', [
            'to' => '/clients',
        ]);
    }
}
