<?php namespace App\Controllers\Admin;

use App\Core\AppController;
use App\Core\Request;
use App\Core\Router;
use App\Core\View;
use App\Helpers\Session;
use App\Model\User;

class LoginController extends AppController
{
    public function __construct()
    {
        if(Session::has('user'))
            Router::redirect('Dash');

        $this->loadForAdmin();
        parent::__construct();
    }

    public function index()
    {
        View::setLayout('login');
        return View::render([
            'title' => 'Panel Administracyjny - logowanie'
        ]);
    }

    public function check(Request $request)
    {
        if($user = User::select(['name', 'id', 'password'])->where(['name', '=', $request->get('name')])->findOrFail()) {
            if (password_verify($request->get('password'), $user['password'])) {
                unset($user['password']);
                Session::set(['user' => $user]);
                Router::redirect('Dash');
            }
            Session::msg('Błędne hasło', 'danger');
        } else
            Session::msg('Użytkownik nie istnieje', 'danger');

        Router::redirect('Login');
    }
}
