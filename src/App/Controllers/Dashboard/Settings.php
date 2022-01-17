<?php

namespace App\Controllers\Dashboard;

use App\Models\DashboardSettings;
use Core\Libs\Request;
use Core\Libs\Response;
use Core\Libs\Validator;
use Core\Libs\Session;

class Settings extends DashboardMainController
{
    /**
     * @var DashboardSettings
     */
    private $dashboardSettings;


    /**
     * Settings constructor.
     * @param DashboardSettings $dashboardSettings
     */
    public function __construct(DashboardSettings $dashboardSettings)
    {
        parent::__construct();

        $this->view->setLayout('dashboard');

        $this->dashboardSettings = $dashboardSettings;
    }

    public function index()
    {
        $email = VIEW_DIR . 'email/email.php';
        $data['email_view'] = file_get_contents($email);

        $this->view->render('Dashboard/settings', $data);
    }

    public function template_email(Request $request)
    {
        $new_email = $request->post('edit_email_text', 'html_entity_decode');

        $email = VIEW_DIR . 'email/email.php';

        if(file_put_contents($email, $new_email)){

            echo '<div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Промените са записани!</div>';

        }else{

            echo '<div class="alert alert-danger alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Грешка!</div>';
        }

    }

    public function fetch_basic()
    {
        $result = ($this->dashboardSettings->fetch_basic());

        print (Response::json($result));
    }
    


    public function update_basic(Request $request, Validator $validator)
    {
        $_settings = $this->table("tbl_settings")->where('settings_name', '=', 'basic')
                    ->get(\PDO::FETCH_ASSOC);
        $profile = [
            'hotelname'=>$request->post('profile_hotel_name'),
            'address'=>$request->post('profile_address'),
            'city'=>$request->post('profile_city'),
            'country'=>$request->post('profile_country'),
            'email'=>$request->post('profile_email'),
            'phone'=>$request->post('profile_telefon'),
            'web'=>$request->post('profile_web'),
            'currency'=>$request->post('currency'),
            'weekday'=>$request->post('weekend')
        ];

        $_profile = serialize($profile);

        $data = [
            'settings_name'=>'basic',
            'settings_value'=>$_profile
        ];

        if (!empty($_settings)){
            if($this->dashboardSettings->table('tbl_settings')
                ->where('settings_name', '=', 'basic')
                ->update($data)){
                echo 1;

            } else {
                echo 0;
            }

        } else {
            if($this->dashboardSettings->table('tbl_settings')
                ->insert($data)){
                echo 1;

            } else {
                echo 0;
            }
        }


    }
}