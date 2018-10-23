<?php

namespace App\Controllers\Dashboard;

use Core\Controller;
use Core\Libs\Request;
use Core\Libs\Session;
use Core\Libs\Validator;
use Core\Libs\Csrf;

/**
 * Class DashboardLogin
 * @package App\Controllers\Dashboard
 */
class DashboardLogin extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * login
     */
    public function login()
    {
        view('Dashboard/login');
    }

    /**
     * @param Csrf $csrf
     * @param Validator $validator
     * @param Request $request
     */
    public function verify(Csrf $csrf, Validator $validator, Request $request)
    {
        $data['errors'] = '';

        $validator->make('username', 'потребител', ['required'])
            ->make('password', 'парола', ['required']);

        if ($validator->run() === false) {
            $data['errors'] = $validator->errors('', '<li>', '</li>', '');
            view('Dashboard/login', $data);

        } else {
            if ($csrf->csrf_validate() === false) {
                $request->session->setFlash('error', '<div class="alert alert-danger" role="alert">!!! Oops - Wrong token !!!</div>');
                redirect(route('login', [], 'get'));
            }

            $user = $request->post('username');
            $password = $request->post('password');

            $user_info = $this->table('tbl_user')
                ->where('user', '=', $user)->getone(\PDO::FETCH_ASSOC);

            if (password_verify($password, $user_info['pass']) === false) {
                $request->session->setFlash('error', '<div class="alert alert-danger" role="alert">
                                            грешен потребител или парола!</div>');
                redirect(route('login', [], 'get'));

            } else {

                $request->session->store('login.user', $user);
                $request->session->store('login.user_type', $user_info['type']);
                $request->session->store('login.time', time());

                $update_data = [
                    'isonline'=>1,
                    'timefrom'=>time()
                ];

                if ($this->table('tbl_user')->where('user', '=', $user)
                    ->update($update_data))
                {
                    redirect(route('dashboard', [], 'get'));
                }

            }
        }

    }

    public function logout(Request $request)
    {
        $user = sessionData('login.user');
        $update_data = [
            'isonline'=>0,
            'timefrom'=>0
        ];

        if ($this->table('tbl_user')->where('user', '=', $user)
            ->update($update_data))
        {
            $request->session->delete('login');
            redirect(route('dashboard', [], 'get'));
        }
    }
}