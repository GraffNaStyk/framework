<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Facades\Cache\Cache;
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

    public function index(int $page = 1): string
    {
        Pagination::make(Client::class, $page, '/clients/page');

        return $this->render([
            'clients' => Cache::remember(50, function () use ($page) {
                return Client::select()->paginate($page)->get();
            }),
        ]);
    }

    public function add(): string
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
            'to' => '/clients',
        ]);
    }

    public function update(Request $request): void
    {

    }

    public function show(int $id): ?string
    {
        $client = Client::select()->where('id', '=', $id)->exist();

        if ($client) {
            return $this->render(['client' => $client]);
        } else {
            $this->redirect('/clients');
        }
    }

    public function edit(int $id): string
    {
        return $this->render();
    }

    public function delete(Request $request): string
    {
        Client::delete()->where('id', '=', $request->get('id'))->exec();

        return $this->sendSuccess('Usunięto', [
            'to' => '/clients',
        ]);
    }
}
