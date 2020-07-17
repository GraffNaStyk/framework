<?php namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Facades\Http\View;
use App\Model\ {
    Correspondence,
    User
};

class DashController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->set(['page' => ['title' => 'Panel Administracyjny']]);
        View::layout('admin');
        Auth::guard();
    }

    public function index()
    {
        return View::render(['users' => User::all()]);
    }
    
    public function users()
    {
        return $this->response(User::select(['name as text', 'id as value'])->get());
    }
}
