<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Events\UserLoginEvent;
use App\Facades\Http\Request;
use App\Facades\Http\Response;
use App\Helpers\Pagination;
use App\Models\Client;
use App\Validators\ClientValidator;

class ClientsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request, Client $client, int $page = 1): Response
    {
        return $this->render([
            'clients' => $client->select()->get()
        ]);
    }

    public function add(): Response
    {
        return $this->render();
    }

    public function store(Request $request, ClientValidator $validator): Response
    {
        if (! $this->validate($request->all(), $validator)) {
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
