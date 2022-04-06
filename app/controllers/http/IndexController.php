<?php

namespace App\Controllers\Http;

use App\Controllers\Controller;
use App\Facades\Http\Request;
use App\Facades\Http\Response;
use App\Facades\Storage\Storage;
use App\Models\Motorcycle;
use App\Models\User;

class IndexController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(User $user, Request $request, Storage $storage): Response
    {
        return $this->render();
    }
    
    public function find(Request $request, Motorcycle $motorcycle): Response
    {
    	$result = $motorcycle->connection('gmoto')
		    ->as('m')
		    ->select(['m.nazwa', 'mmn.url_name_seo_spec'])
		    ->join('motocykle_merged_names as mmn', 'mmn.ID_moto', '=', 'm.ID_moto')
		    ->where('m.nazwa', 'LIKE', '%'.$request->get('query').'%')
		    ->get();
    	
    	return (new Response())->json()->setData($result)->send();
    }
}
