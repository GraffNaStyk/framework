<?php namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Facades\Validator\Validator;
use App\Helpers\Session;
use App\Model\User;
use App\Facades\Http\View;
use App\Facades\Http\Request;

class LoginController extends Controller
{
    public function __construct()
    {
        if (Session::has('user')) {
            $this->redirect('dash');
        }
        parent::__construct();
    }

    public function index()
    {
        View::layout('login');
        return View::render(['title' => 'Panel Administracyjny - logowanie']);
    }

    public function check(Request $request)
    {
        if(!$this->validate($request->all(), [
            'name' => 'string|required|min:3',
            'password' => 'string|required|min:3',
        ])) $this->sendError();
        
        if ($user = User::select(['name', 'id', 'password'])
            ->where(['name', '=', $request->get('name')])
            ->findOrFail()
        ) {
            if (password_verify($request->get('password'), $user['password'])) {
                unset($user['password']);
                Session::set(['user' => $user]);
                $this->sendSuccess('Zalogowano poprawnie', 'dash');
            }
            $this->sendError();
        } else {
            $this->sendError();
        }
    
        $this->sendError();
    }
}
