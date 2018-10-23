<?php
namespace App\Controllers\Dashboard;

use App\Models\DashboardResevations;
use App\Models\FindRooms;
use Core\Libs\Request;
use Core\Libs\Session;
use Core\Libs\DataTables\DataTable as DataTable;
use Core\Libs\Validator;

/**
 * Class Reservations
 * @package App\Controllers\Dashboard
 */
class Reservations extends DashboardMainController
{
    /**
     * @var DashboardResevations
     */
    private $reservation;
    /**
     * @var FindRooms
     */
    private $findRooms;

    /**
     * Resrvations constructor.
     */
    public function __construct(DashboardResevations $dashboardResevations, FindRooms $findRooms)
    {
        parent::__construct();

        $this->view->setLayout('dashboard');

        $this->reservation = $dashboardResevations;
        $this->findRooms = $findRooms;
    }

    /**
     * Ajax
     */
    public function pending()
    {
        echo $this->reservation->count_pending_reservations();
    }


    public function reservations()
    {
        view('Dashboard/reservations');
    }

    /**
     * @param DataTable $data_table
     */
    public function data_table(DataTable $data_table)
    {

        $columns = array(
            array('db' => 'reservation_id', 'dt' => 0),
            array('db' => 'created', 'dt' => 1),
            array('db' => 'qty', 'dt' => 2),
            array('db' => 'room_names', 'dt' => 3),

            // array( 'db' => 'checkin',       'dt' => 3 ),
            array(
                'db' => 'checkin',
                'dt' => 4,
                'formatter' => function ($d, $row) {
                    return date('Y-m-d', strtotime($d));

                }),
            // array( 'db' => 'checkout',      'dt' => 4 ),
            array(
                'db' => 'checkout',
                'dt' => 5,
                'formatter' => function ($d, $row) {
                    return date('Y-m-d', strtotime($d));

                }),
            array('db' => 'client_name', 'dt' => 6),
            array('db' => 'status', 'dt' => 7)

        );

        $data_table->fetch('tbl_datatables', 'reservation_id', $columns);
    }

    /**
     * @param $id
     */
    public function reservation($id)
    {
        $data['reservation'] = $this->reservation->fetch_reservation($id);

        $data['total'] = 0;

        foreach ($data['reservation'] as $key => $res) {
            $data['total'] += $res->price;
        }

        $data['count_room'] = array_count_values(array_column($data['reservation'], 'room_type_id'));

        view('Dashboard/reservation', $data);

    }

    /**
     * @param Request $request
     * @param Validator $validator
     */
    public function reservationEditStatus(Request $request, Validator $validator)
    {
        $status = $request->post('status');
        $rid = $request->post('reservation_id');

        $validator->make('status', 'status', ['required']);
        if ($validator->run() === false) {

            echo $validator->errors();

        } else {
            $allReservedRooms = $this->reservation->fetch_reservation($rid);

            $error = $this->isReservedRoomsAvailableNow($rid, $allReservedRooms);

            if($error){
                echo $error;
                die;

            }  else {
                $update = $this->reservation->table('tbl_reservation')
                    ->where('reservation_id', '=', $rid)
                    ->update(['status' => $status]);

                if ($update > 0) {
                    echo 1;
                }
            }

        }

    }

    /**
     * @param Request $request
     * @param Validator $validator
     */
    public function reservationEditDate(Request $request, Validator $validator)
    {
        $new_checkin = $request->post('arrival');
        $new_checkout = $request->post('departure');
        $reservation_id = $request->post('reservation_id');

        $allReservedRooms = $this->reservation->fetch_reservation($reservation_id);

        $validator->make('arrival', '(настаняване)', ['required', 'date'])->
                    make('departure', '(напускане)', ['required', 'date', 'after:arrival']);

        if ($validator->run() == false) {
            echo $validator->errors();

        } else {

            /* проверява дали стаята с това име е свободна
            * пример: резервация за  22 - 24 / стая 101
            * Ако удължим престоя до 26 стая 101 може да е резервирана от друг за (25-28)
            */
            $error = $this->isReservedRoomsAvailableNow($reservation_id, $allReservedRooms, $new_checkin, $new_checkout);

            if ($error){
                echo $error;
                exit;

            } else {

                foreach ($allReservedRooms as $k=>$reservation) {
                    $price = calculate_price($new_checkin, $new_checkout, $reservation->room_type_id);

                    $arr = [
                        'checkin' => $new_checkin,
                        'checkout' => $new_checkout,
                        'price'=>$price['total']
                    ];

                    $update[] = $this->reservation->table('tbl_reservation')
                        ->where([
                            ['reservation_id', '=', $reservation_id],
                            ['id', '=', $reservation->id]
                    ])->update($arr);
                }

                echo 1;
            }
        }
    }

    /**
     * @param $reservation_id
     * @param $allReservedRooms
     * @param string $checkin
     * @param string $checkout
     * @return string
     */
    protected function isReservedRoomsAvailableNow($reservation_id, $allReservedRooms, $checkin = '', $checkout='')
    {
        /*
         * проверява дали стаята с това име е свободна
         * пример: резервация за  22 - 24 / стая 101
         * Ако удължим престоя до 26 стая 101 може да е резервирана от друг за (25-28)
         *
         */

        if(!$checkin){
            $checkin = $allReservedRooms[0]->checkin;
        }
        if(!$checkout){
            $checkout = $allReservedRooms[0]->checkout;
        }
        $reserved_room_id = (array_column($allReservedRooms, 'room_name', 'room_id'));

        $room_type = (array_column($allReservedRooms, 'room_type_id', 'room_id')); // ['room_id'=>'room_type_id']

        $error="";

        foreach ($reserved_room_id as $id => $room_name) {
            if ($this->findRooms->checkIfRoomIsBooked(
                    $checkin,
                    $checkout,
                    $id,
                    $reservation_id) == true
            ){
                $available_rooms = $this->findRooms->
                get_available_room_names($checkin, $checkout, $room_type[$id]);
                $available_room_names = implode(", ", array_column($available_rooms, 'room_name'));

                if ($available_room_names){
                    $error .= "Стая " .$room_name . " е резервирана за този период! " .
                        " Свободните стаи от този тип са: " . $available_room_names . "<br>";

                } else {
                    $error .= "Стая " .$room_name . " е резервирана за този период! " .
                        " Няма други свободни стаи от този тип! <br>";
                }

            }
        }

        return $error;

    }

    /**
     * @param $id
     */
    public function reservationDelete($id)
    {
        if ($this->reservation->table('tbl_reservation')
                ->where('reservation_id', '=', $id)
                ->delete() > 0 &&
            $this->reservation->table('tbl_clients')
                ->where('reservation_unique_id', '=', $id)
                ->delete() > 0
        ) {
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * @param Request $request
     */
    public function reservationCutRoom(Request $request)
    {
        $id = $request->post("id", "int");

        $update_data = ["room_id" => ""];

        if ($this->findRooms->table("tbl_reservation")
            ->where("id", "=", $id)->update($update_data)
        ) {
            echo 1;
        }
    }

    // Reserved Rooms
    /**
     * @param Request $request
     */
    public function fetchAvailableRoomNames(Request $request)
    {
        $checkin = $request->post('checkin');
        $checkout = $request->post('checkout');
        $room_type_id = $request->post('room_type_id', 'int');

        $room_names = $this->findRooms->get_available_room_names($checkin, $checkout, $room_type_id);

        foreach ($room_names as $room) {
            echo '<option value="' . $room['id'] . '">' . $room['room_name'] . '</option>';
        }

    }

    /**
     * @param Request $request
     */
    public function attach_room(Request $request)
    {
        $data = [
            'room_id' => $request->post('room_id')
        ];

        $update = $this->reservation->table('tbl_reservation')
            ->where('id', '=', $request->post('primary_id'))
            ->update($data);

        if ($update > 0) {
            echo 1;
        }
    }

    public function add_room(Request $request)
    {
        $post = $request->postAll();
        $now = date('Y-m-d H:i:s');
        $price = calculate_price($post['arrival'],$post['departure'], $post['room_type_id']);
        $insert_data = [
            'reservation_id'=>$post['reservation_id'],
            'room_id'=>$post['room_id'],
            'room_type_id'=>$post['room_type_id'],
            'checkin'=>$post['arrival'],
            'checkout'=>$post['departure'],
            'adults'=>$post['adults'],
            'child' =>$post['child'],
            'qty'=>1,
            'created'=>$now,
            'price'=>$price['total'],
            'status'=>$post['status'],
            'client_id'=>$post['client_id']
        ];
        if ($this->reservation->table('tbl_reservation')->insert($insert_data)) {
            echo 1;
        }
    }

    public function reservationDeleteRoom(Request $request)
    {
        $id = $request->post("id", "int");
        $reservation_id = $request->post("reservation_id");
        $count = $this->reservation->table('tbl_reservation')->where('reservation_id', '=', $reservation_id)
            ->get(\PDO::FETCH_ASSOC);

        if (count($count) == 1){
            echo 'Не може да изтриете единствен запис!';
            die;
        }
        if ($this->findRooms->table("tbl_reservation")
                            ->where("id", "=", $id)->delete()
        ) {
            echo 1;
        }
    }
}