<?php

namespace App\Controllers\Dashboard;

use Core\Libs\Request;
use Core\Libs\Session;
use Core\Libs\Utils\Arrays;
use Core\Libs\Validator;

class DashboardUsers extends DashboardMainController
{
    
    public function __construct()
    {
        parent::__construct();

        $this->view->setLayout('dashboard');
    }

    public function users()
    {
        $data['users'] = $this->table('tbl_user')->get(\PDO::FETCH_ASSOC);

        view('Dashboard/users', $data);
    }

    public function userProfile($user = null)
    {
        if ($user === null){
            $main_user_name = sessionData('login.user');

        } else {
            $main_user_name = $user;
        }

        $data['profile'] = $this->table('tbl_user')->where('user', '=', $main_user_name)
                        ->getone(\PDO::FETCH_OBJ);

        view('Dashboard/user_profile', $data);
    }


    public function newUser(Validator $validator, Request $request)
    {
        $password = passwordHash($request->post('password'));

        $validator->make('user', 'потребител', ['required', 'unique:tbl_user.user'])
            ->make('email', 'email', ['required', 'email'])
            ->make('password', 'парола', ['required']);

        if ($validator->run() === false) {
            $data['errors'] = $validator->errors();
            view('Dashboard/user_new', $data);

        } else {
            $insert_data = [
                'user' => $request->post('user'),
                'email'=> $request->post('email'),
                'pass'=> $password
            ];

            $this->table('tbl_user')->insert($insert_data);

            redirect('booking-dashboard/users');
        }

    }

    public function updateUserProfile(Request $request, Validator $validator)
    {
        $password = passwordHash($request->post('password'));
        $user = $request->post('user');

        $validator->make('email', 'email', ['required', 'email']);

        if ($validator->run() === false) {
            echo $validator->errors();

        } else {
            $update_data = [
                'email'=> $request->post('email'),
                'pass'=> $password
            ];

            if ($request->post('password') == null ){
                Arrays::pull($update_data, 'pass');
            }
            $this->table('tbl_user')->where('user', '=', $user)
                ->update($update_data);

            echo alert("success", "Промените са запазени");
        }
    }

    public function deleteUser(Request $request)
    {
        $user = $request->post('user');
        $userId = $request->post('id');

        if ($userId == 1 ){
            echo "Този потребител не може да бъде изтрит";

        } else {
            if($this->table('tbl_user')->where('user', '=', $user)->delete()){
                echo 1;
            } else {
                echo "Грешка";
            }
        }

    }

}