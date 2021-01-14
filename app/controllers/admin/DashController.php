<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Auth;
use App\Facades\Http\Request;
use App\Facades\Http\View;
use App\Helpers\Storage;
use App\Model\User;

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
        return View::render([
                'users' => User::select(['name as value', 'password as text'])->get()
            ]);
    }
    
    public function users($name)
    {
        return $this->response(
            User::select(['name as text', 'password as value'])->get()
        );
    }
    
    public function upload(Request $request)
    {
        Storage::disk('public')->upload($request->file('file'));
    }
}
