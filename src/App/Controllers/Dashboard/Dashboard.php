<?php

namespace App\Controllers\Dashboard;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use App\Models\DashboardPrices;
use App\Models\DashboardResevations;
use Core\Libs\Session;
use App\Models\DashboardRoom;

class Dashboard extends DashboardMainController
{
    protected $dashboardRoom;

    public function __construct(DashboardRoom $dashboardRoom)
    {
        parent::__construct();

        $this->view->setLayout('dashboard');
        $this->dashboardRoom = $dashboardRoom;
    }

    public function index(DashboardResevations $dashboardResevations, DashboardPrices $dashboardPrices)
    {
        $data['unavailableToday'] = $dashboardResevations->getcurrentReservations();
        $data['arrivalToday'] = $dashboardResevations->arrivalToday();
        $data['departureToday'] = $dashboardResevations->departureToday();
        $data['capacity_adults'] = $this->dashboardRoom->capacity()->countAdults();
        $data['capacity_child'] = $this->dashboardRoom->capacity()->countChild();
        $data['count_rooms'] = $this->dashboardRoom->capacity()->countRooms();
        $data['occupied_room'] = count($dashboardResevations->get_occupied_rooms());

        $activeSeasonId = $dashboardPrices->activeSeason()->season_id;
        If ($activeSeasonId){
            $data['room_type_price'] = $dashboardPrices->getSeasonRoomTypePrice($activeSeasonId);
        } else {
            $data['room_type_price'] = $this->table('tbl_room_type')->get(\PDO::FETCH_ASSOC);
        }

        view('Dashboard/dashboard', $data);
    }

}