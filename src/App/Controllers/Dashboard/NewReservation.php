<?php

namespace App\Controllers\Dashboard;

use App\Models\BookingModel;
use App\Models\DashboardResevations;
use App\Models\DashboardRoom;
use App\Models\FindRooms;
use Core\Libs\Request;
use Core\Libs\Response;
use Core\Libs\Session;
use Core\Libs\Validator;

/**
 * Class NewReservation
 * @package App\Controllers\Dashboard
 */
class NewReservation extends DashboardMainController
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
     * @var DashboardRoom
     */
    private $dashboardRoom;
    /**
     * @var BookingModel
     */
    private $bookingModel;


    /**
     * Resrvations constructor.
     */
    public function __construct(DashboardResevations $dashboardResevations, FindRooms $findRooms,
                                DashboardRoom $dashboardRoom, BookingModel $bookingModel)
    {
        parent::__construct();

        $this->view->setLayout('dashboard');

        $this->reservation = $dashboardResevations;
        $this->findRooms = $findRooms;
        $this->dashboardRoom = $dashboardRoom;
        $this->bookingModel = $bookingModel;
    }


    public function index()
    {
        $dateTime = new \DateTime();

        $now = $dateTime->format("Y-m-d");
        $tomorrow = $dateTime->add(new \DateInterval('P1D'));

        $data['errors'] = '';

        $data['now'] = $now;
        $data['tomorrow'] = $tomorrow->format('Y-m-d');

        $data['rooms'] = $this->reservation->table('tbl_rooms')->orderBy('room_name')->get();
        $data['reservation_id'] = generate_unique_id(6);
        view('Dashboard/add_reservation', $data);
    }

    public function availableRoomsForNewReservation(Request $request)
    {
        $checkin = $request->post('arrival');
        $checkout = $request->post('departure');
        $added = $request->post('added');

        if (!$checkin || !$checkout) {
            echo '<p style="color: red">не сте избрал дата</p>';

        } else {
            echo drop_down_rooms($checkin, $checkout, $added);
        }
    }

    public function add_room(Request $request)
    {
        /*
         $chekin = $request->post('checkin');
         $chekout = $request->post('checkout');
         $data_added['room_id ']= $request->post('room_id');
         $data_added['room_name'] = $request->post('room_name');
         $data_added['room_type_id'] = $request->post('room_type_id');
         $data_added['adults'] = $request->post('adults');
         $data_added['child'] =$request->post('child');
         $data_added['qty'] = $request->post('qty');
        */

        $data_added = $request->postAll();

        if(!$data_added['room_id']){
            echo "has-error";

            return;
        }

        $calculate_price = calculate_price($data_added['arrival'], $data_added['departure'], $data_added['room_type_id']);
        $data_added['total'] = $calculate_price['total'];

        $request->session->push('dashboard_added', $data_added);

        echo Response::json($request->session->getData('dashboard_added'));
    }

    public function display_added(Request $request)
    {
        $added = $request->session->getData('dashboard_added');
        $total_sum = array_sum(array_column($added, 'total'));
        $total_sum = ($total_sum == 0) ? "" :$total_sum . ' ' . settings()->currency();
        $output = '<table class="table table-stripped">';
        $output .= '<tr>';
        $output .= '<th>стая</th>';
        $output .= '<th>възр</th>';
        $output .= '<th>деца</th>';
        $output .= '<th>цена</th>';
        $output .= '<th>&nbsp;</th>';
        $output .= '</tr>';
        foreach ($added as $key => $room) {
            $output .= '<tr>';
            $output .= '<td>' . $room['room_name'] . '</td>';
            $output .= '<td>' . $room['adults'] . '</td>';
            $output .= '<td>' . $room['child'] . '</td>';
            $output .= '<td>' . $room['total'] . ' ' . settings()->currency() . '</td>';
            $output .= '<td><a class="btn btn-danger" type="button"
                        onclick="delete_added_room(' . $key . ')">&times;
                            </a> ' . '</td>';
            $output .= '</tr>';

        }
        $output .= '<tr>';
        $output .= '<td colspan="4">';
        $output .= '<span style="font-weight: bold">тотал: ' . $total_sum . '</span>';
        $output .= '</td>';
        $output .= '</tr>';


        $output .= '</table>';
        echo $output;
    }

    /**
     *
     */
    public function update_added_room(Request $request)
    {
        $added = $request->session->getData('dashboard_added');

        $ids = array_column($added, 'room_id');

        echo implode(', ', $ids);
    }

    /**
     * @param Request $request
     */
    public function delete_added_room(Request $request)
    {
        $key = $request->post('key', 'string');

        if ($key == '') {
            $request->session->delete('dashboard_added');

        } else {
            $request->session->delete('dashboard_added.' . $key);

        }

        $added = $request->session->getData('dashboard_added');

        $ids = array_column($added, 'room_id');

        echo implode(', ', $ids);
    }

    public function get_room_guest(Request $request)
    {
        $room_id = $request->post('room_id');

        $result = $this->dashboardRoom->roomGetInfo($room_id);

        print(Response::json(['adults' => $result['adults'], 'child' => $result['child']]));
    }

    public function new_reservation(Request $request, Validator $validator)
    {
        $data['added'] = $request->session->getData('dashboard_added');

        if(empty($data['added'])){
            $validator->set_error('added_rooms', 'Изберете стая!');
        }

        $data['errors'] = '';

        $data['checkin'] = $request->post('arrival');
        $data['checkout'] = $request->post('departure');
        $data['status'] = "от служител";
        $data['reservation_id'] = generate_unique_id(6);
        $data['res_code'] = '';

        // ---- Validate form ----
        $validator->make('name', '(Име)', ['required', 'name', 'max:30'])->
        make('email', '(e-mail)', ['required', 'email', 'max:60'])->
        make('telefon', '(Телефон)', ['required', 'max:15', 'is_numeric'])->
        make('text', '(Текст)', ['max:250'])->
        make('arrival', '(настаняване)', ['required', 'date'])->
        make('departure', '(заминаване)', ['required', 'date', 'after:arrival']);

        if ($validator->run() === false) {
            $data['errors'] = $validator->errors('', '<li>', '</li>', '');
            view('Dashboard/add_reservation', $data);

        } else {
            $data['name'] = $request->post('name');
            $data['email'] = $request->post('email');
            $data['telefon'] = $request->post('telefon');
            $data['text'] = $request->post('text');

            // дали някой е резервирал преди нас ?
//                if ($this->check_for_available() === true) {
            // Запис в БД
            $this->bookingModel->booking($data);
            $request->session->delete('dashboard_added');
            redirect('booking-dashboard/reservations');

        }

    }
}