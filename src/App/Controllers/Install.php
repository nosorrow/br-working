<?php


namespace App\Controllers;


use Core\Controller;
use Core\Libs\Request;
use Core\Libs\Validator;

class Install extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $tables = $this->execute_sql("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN);

        $needed_tables = array(
            7 => 'tbl_reservation_comments',
            14 => 'tbl_settings',
            15 => 'tbl_user',
            0 => 'tbl_amenities',
            1 => 'tbl_amenities_translate',
            2 => 'tbl_clients',
            10 => 'tbl_room_type_translate',
            3 => 'tbl_datatables',
            4 => 'tbl_languages',
            5 => 'tbl_prices',
            6 => 'tbl_reservation',

            8 => 'tbl_room_amenities',
            9 => 'tbl_room_type',

            11 => 'tbl_rooms',
            12 => 'tbl_seasons',
            13 => 'tbl_session',

            );

        if(empty(array_diff($needed_tables, $tables))){
            redirect("search");
        }

    }

    public function migrate($mode = null)
    {
        if($mode == "demo"){
            $file = APPLICATION_DIR . "sql/demo_br_booking.sql";

        } else {
            $file = APPLICATION_DIR . "sql/empty_br_booking.sql";
        }

        if(file_exists($file)){
            $sql = file_get_contents($file);

        } else {
            die("SQL файлът не е намерен");
        }

        if($this->execute_sql($sql)){

            return true;
        }
    }

    public function install($mode = null)
    {
        if($this->migrate($mode) == true && $this->checkForAdministrator() !== true){
            view("install_user");
        } else {
            redirect('booking-dashboard');
        }
    }

    private function checkForAdministrator()
    {
        $count = (int)($this->execute_sql("SELECT COUNT(*) as count FROM tbl_user WHERE id = 1 ")->fetchColumn());

        if ($count == 1){
            return true;

        } else {
            return false;
        }
    }

    public function installAdministrator(Validator $validator, Request $request)
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

            redirect('booking-dashboard');
        }
    }

}