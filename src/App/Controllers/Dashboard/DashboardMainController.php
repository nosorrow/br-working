<?php

namespace App\Controllers\Dashboard;

use Core\Controller;
use Core\Libs\Session;

/**
 * Class DashboardMainController
 * @package App\Controllers\Dashboard
 */
class DashboardMainController extends Controller
{
    /**
     * DashboardMainController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if(empty(sessionData('login.user'))){
            redirect(route('login', [], 'get'));
        }
    }

}