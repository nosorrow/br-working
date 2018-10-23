<?php

namespace App\Controllers\Dashboard;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use App\Models\DashboardPrices;
use Core\Libs\Request;
use Core\Libs\Session;
use Core\Libs\Validator;


class Prices extends DashboardMainController
{
    /**
     * @var DashboardPrices
     */
    private $dashboardPrices;

    /**
     * @var DashboardRoom
     */

    public function __construct(DashboardPrices $dashboardPrices)
    {
        parent::__construct();
        $this->view->setLayout('dashboard');

        $this->dashboardPrices = $dashboardPrices;
    }

    public function seasons()
    {
        $data['seasons'] = $this->dashboardPrices->getSeasons();
        $data['room_types'] = $this->dashboardPrices->get_room_types_name();

        view('Dashboard/seasons', $data);
    }

    public function add_season()
    {
        $data['room_type'] = $this->dashboardPrices->get_room_types_name();
        view('Dashboard/season_new', $data);
    }

    public function insert_season(Request $request, Validator $validator)
    {
        $range = json_decode($request->post('range', 'html_entity_decode'));

        if ($validator->date($range->start) && $validator->date($range->end)) {
            $start = $range->start;
            $end = $range->end;
        } else {

            $request->session->setFlash('msg', 'Невалиден период');
            redirect('booking-dashboard/seasons');
        }
        $new_season = [
            'start_date' => $start,
            'end_date' => $end,
            'season_name' => $request->post('season_name')
        ];

        $post = $request->postAll();

        if ($this->dashboardPrices->insertNewSeasonPrices($new_season, $post)) {
            $request->session->setFlash('msg', 'Success');
            redirect('booking-dashboard/seasons');
        } else {
            $request->session->setFlash('msg', 'Error');
            redirect('booking-dashboard/seasons');
        }
    }


    public function edit_season($id = null)
    {
        $data['season'] = $this->dashboardPrices->getSeason($id);

        $data['room_type_price'] = $this->dashboardPrices->getSeasonRoomTypePrice($id);

        view('Dashboard/season_price', $data);

    }

    public function update_season_price(Request $request, Validator $validator)
    {
        $range = json_decode($request->post('range', 'html_entity_decode'));

        if ($validator->date($range->start) && $validator->date($range->end)) {
            $start = $range->start;
            $end = $range->end;
        } else {

            $request->session->setFlash('msg', 'Невалиден период');
            redirect('booking-dashboard/seasons');
        }

        $new_season = [
            'season_id' => $request->post('season_id'),
            'start_date' => $start,
            'end_date' => $end,
            'season_name' => $request->post('season_name')
        ];

        $post = $request->postAll();

        $result = $this->dashboardPrices->updateNewSeasonPrices($new_season, $post);

        if ($result == 0) {
            echo "Няма променени данни";

        } else {
            echo "Успешна актуализация";
        }
    }

    public function season_delete($id)
    {
        $delseason = $this->table('tbl_seasons')->where('season_id', '=', $id)->delete();
        $delprice = $this->table('tbl_prices')->where('price_season_id', '=', $id)->delete();

        if($delseason>0 &&  $delprice>0){
            echo 1;
        }
    }
}