<?php

namespace App\Controllers\Dashboard;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use App\Models\FindRooms;
use Core\Libs\Request;
use Core\Libs\Session;
use Core\Libs\Validator;
use App\Models\DashboardRoom;

class Rooms extends DashboardMainController
{
    private $dashboardRoom;
    /**
     * @var FindRooms
     */
    private $findRooms;

    /**
     * Rooms constructor.
     * @param DashboardRoom $dashboardRoom
     */
    public function __construct(DashboardRoom $dashboardRoom, FindRooms $findRooms)
    {
        parent::__construct();

        $this->view->setLayout('dashboard');
        $this->dashboardRoom = $dashboardRoom;

        $this->findRooms = $findRooms;
    }

    public function room_names()
    {
        $data['types'] = $this->findRooms->table('tbl_room_type')
            ->field('id', 'room_type')
            ->get(\PDO::FETCH_ASSOC);

        view('Dashboard/room_names', $data);
    }

    public function fetch_rooms()
    {
        $room_names = $this->dashboardRoom->getRoomNames("type");

        $output = '<table id="table" class="table table-striped table-hover table-bordered">
                <thead>
                <th id="name" onclick="sortTable(0,1)"><span class="sort fa fa-sort-amount-desc" style="cursor:pointer"></span> име</th>
                <th id="type" onclick="sortTable(1,2)"><span class="sort fa fa-sort-amount-desc" style="cursor:pointer"></span> тип</th>
                <th></th>
                </thead>
                <tbody>';

        foreach ($room_names as $room) {
            $output .= '<tr>';
            $output .= '<td>' . $room['name'] . '</td>';
            $output .= '<td>' . $room['type'] . '</td>';
            $output .= '<td class="text-center"><button type="button" class="btn btn-danger" onclick="deleteRoom('. $room['name'] . ')"
                        data-room="' .$room['name']. '">
                        <i class="fa fa-trash" aria-hidden="true"></i></button></td>';

            $output .= '</tr>';

        }

        $output .= '</tbody></table>';

        print $output;
    }

    public function new_room(Request $request, Validator $validator)
    {
        $validator->make('room_name' , 'име', ['required', 'unique:tbl_rooms.room_name'])
                    ->make('room_type_id' , 'тип на стаята', ['required', 'integer']);

        if ($validator->run() === false) {
            echo $validator->errors();
            return;

        } else {
            $room_type_id = $request->post('room_type_id', 'int');
            $room_name = $request->post('room_name');

            if ($this->dashboardRoom->countRoomByTypes($room_type_id) ==
                $this->dashboardRoom->roomTypesQty($room_type_id)
            ) {
                $validator->set_error('name', "Не може да добавяте повече стаи от този тип");
                echo $validator->errors();
                return;
            }

            $insert = [
                'room_name'=>$room_name,
                'room_type_id'=>$room_type_id
            ];

            if($this->dashboardRoom->table('tbl_rooms')->insert($insert)>0){
                echo 1;
            }
        }

    }

    public function edit_room_status(Request $request)
    {
        $room_name = $request->post('room_name_modal');
        $update = [
            'room_status'=>$request->post('modal_status')
        ];

        $update = $this->dashboardRoom->table('tbl_rooms')
                            ->where('room_name', '=', $room_name)
                            ->update($update);
        if ($update>0) {
            echo 1;
        }else {
            echo 0;
        }
    }

    public function delete(Request $request)
    {
        $room_name = $request->post('room_name');

        if ($this->dashboardRoom->table('tbl_rooms')->where('room_name','=',$room_name)->delete()) {
            echo "изтрит";
        }
    }
}