<?php

namespace App\Controller;

use Core\Config;
use Core\Controller;
use Core\Session;

class AuthController extends Controller
{

    public function loginAction(): string
    {
        $login = (isset($_POST['login'])) ? strtolower($_POST['login']) : '';
        $password = (isset($_POST['password'])) ? $_POST['password'] : '';
        $users = Config::get('users');
        $userNames = array_keys($users);

        if (in_array($login, $userNames) && ($password == $users[$login]['password'])) {
            Session::set('user', [
                'login' => $login,
                'roles' => $users[$login]['roles']
            ]);

            $result = [
                'error' => false,
            ];
        } else {
            $result = [
                'error'   => true,
                'message' => 'Wrong login or password!'
            ];
        }

        return json_encode($result);
    }


    public function logoutAction()
    {
        Session::delete('user');
        header('Location: ' . '/');
    }

}