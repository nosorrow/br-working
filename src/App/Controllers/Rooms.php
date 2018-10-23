<?php

namespace App\Controllers;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');

use Core\Controller;
use App\Models\FindRooms;
use App\Models\BookingModel;
use Core\Libs\Request;
use Core\Libs\Response;
use Core\Libs\Session;
use Core\Libs\Validator;

/**
 * Class Rooms
 * @package App\Controllers
 */
class Rooms extends Controller
{
    public $findRooms;

    public $bookingModel;

    public $url;

    /**
     * Rooms constructor.
     * @param FindRooms $findRooms
     * @param BookingModel $bookingModel
     */
    public function __construct(FindRooms $findRooms, BookingModel $bookingModel)
    {
        parent::__construct();

        $this->findRooms = $findRooms;
        $this->bookingModel = $bookingModel;
        $this->deleteNotConfirmed($bookingModel);

    }

    /**
     * @param $lang
     */
    public function set_lang($lang)
    {
        set_cookie('lang', $lang);

        if (url()->getPrevious()) {

            url()->redirect_back();

        } else {
            redirect('search');
        }
    }

    /**
     * @param Request $request
     * @param $slug
     * @throws \Exception
     * @internal param $id
     */
    public function roominfo(Request $request, $slug)
    {
        $result_array_key = request_get('r_k');
        $result = $request->session->getData('result');
        $available = $result[$result_array_key]['available'];

        $room = $this->findRooms->getRoomBySlug($slug);

        $data = [
            'errors' => '',
            'room' => $room,
            'result' => $result[$result_array_key],
            'available' => $available,
            'r_k' => $result_array_key,
            'prices' => $this->findRooms->getAllRoomPrices($room['id'])
        ];

        $this->view->render('room', $data);
    }

    /**
     * @throws \Exception
     */
    public function search(Request $request)
    {
        if (empty($_GET)) {
            $request->session->delete('added');
            $request->session->delete('result');
            $request->session->delete('csrf_token');
        }

        $page = 'result';

        $data['errors'] = '';

        $validator = Validator::getInstance();

        $arrival = tr_('настаняване');
        $departure = tr_("напускане");
        $adults = tr_("възрастни");

        $validator->make('checkin', $arrival, ['required', 'date'])->
        make('checkout', $departure, ['required', 'date', 'after:checkin'])->
        make('adults', $adults, ['required', 'integer', 'max:3', 'greater:0']);

        if ($this->request->get('adults', 'int') > $this->findRooms->countAdults()) {

            $validator->set_error('adults',
                sprintf(tr_('Хотелът не разполага с %d места за възрастни'), $this->request->get('adults', 'int'))
            );
        }

        if ($validator->run() === false) {

            $data['errors'] = $validator->errors('', '<li>', '</li>', '<div class="alert alert-danger" role="alert">%s</div>');
            $this->view->render($page, $data);

        } else {

            $checkin = $this->request->get('checkin');
            $checkout = $this->request->get('checkout');
            $adults = $this->request->get('adults', 'int');
            $child = ($this->request->get('child', 'int') > 0) ? $this->request->post('child', 'int') :0;

            $rooms = $this->findRooms->get_available_rooms($checkin, $checkout);
            if (empty ($rooms['room'])) {
                $_flash = tr_('Няма свободни стаи');
                $request->session->setFlash('error', '<div class="alert alert-danger" role="alert">' . $_flash . '</div>');
                $this->view->render($page);
            } else {
                $dates = parseDates($checkin, $checkout);
                $data['rooms'] = $rooms['room'];
                $data['dateStr'] = $dates['dateStr'];
                $data['adults'] = $adults;
                $data['child'] = $child;

                $request->session->store('result', $rooms['room']);
                $request->session->store('interval', $dates['interval']);
                $request->session->store('dateStr', $dates['dateStr']);
                $request->session->store('checkin', $checkin);
                $request->session->store('checkout', $checkout);
                $request->session->store('adults', $adults);
                $request->session->store('child', $child);

                $this->view->render($page, $data);
            }
        }
    }


    /**
     * @param BookingModel $bookingModel
     */
    protected function deleteNotConfirmed(BookingModel $bookingModel)
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval('PT10M'));

        $garbage_time = $date->format('Y-m-d H:i:m');
        try {
            $bookingModel->deleteNotEmailConfirm($garbage_time);

        } catch (\Exception $e) {
            echo 'Reservation Garbage collector error ' . $e->getMessage();
        }
    }

    /**
     * ajax
     * @param Request $request
     */
    public function add_to_bag(Request $request)
    {
        // r_k (result_key) е ключ  от масива result в сесията;
        $result_key = $request->post('r_k', 'int');
        $qty = $request->post('quantity', 'int'); //1
        $adults = $request->post('add_adults', 'int');
        $child = $request->post('add_child', 'int');
        $id = $request->session->getData('result.' . (string)$result_key . '.id');
        $available = (int)$request->session->getData('result.' . (string)$result_key . '.available');//5
        $total = (int)$request->session->getData('result.' . (string)$result_key . '.total');

        if (!empty($request->session->getData('added'))) {
            $added_room_type_id = array_column($request->session->getData('added'), 'room_type_id');
            $val = array_count_values($added_room_type_id); //3
            $_qty = ($qty < ($available - (int)$val[$id])) ? $qty :($available - (int)$val[$id]); // 5-3 = 2

        } else {
            $_qty = $qty;
        }

        for ($i = 1; $i <= $_qty; $i++) {
            $request->session->push('added', ['room_type_id' => $id, 'qty' => 1,
                'adults' => $adults, 'child' => $child, 'total' => $total,
                'r_k' => $result_key]);
        }

        $result['qty'] = $_qty;
        $result['room_type'] = $request->session->getData('result.' . (string)$result_key . '.room_type');

        echo Response::json($result);

    }

    /**
     * ajax show added rooms
     */
    public function display_added(Request $request)
    {
        $added = $request->session->getData('added');
        $result = $request->session->getData('result');

        if (count($added) > 0) {

            $display = '<table class="table table-striped table-hover">';
            $price = 0;
            $qty = 0;
            $tr_adults = tr_('възрастни');
            $tr_qty = tr_('бр. от тип');
            $tr_price = tr_('цена');
            $tr_reserve = tr_('Резервирай');
            $tr_total = tr_('Общо');
            $tr_child = tr_('деца');
            foreach ($added as $key => $value) {
                $child = (!$value['child']) ? '' :' | ' . $tr_child . ' ' . $value['child'];
                $price = $price + $value['total'];
                $qty += $value['qty'];
                $display .= '<tr>
                            <td>' . $value['qty'] . ' ' . $tr_qty . ' ' . $result[$value['r_k']]['room_type'] . '
                             - ' . $tr_adults . ' ' . $value['adults'] . $child . '</td>
                            <td>' . $tr_price . ' ' . $value['total'] . ' ' . settings()->currency() . '</td>
                            <td>
                            <button id="btn_delete" data-delete_id="' . $key . '" class="btn btn-danger" style="border-radius: 50%;">
                            <span style="">&times;</span></button>
                            </td>
                        </tr>';

            }

            $display .= '<tr>
                        <td class="display-added">
                        <a href="' . site_url('booking') . '" class="btn btn-success btn-lg">
                        ' . $tr_reserve . ' <span id="count_added" class="badge badge-added">' . $qty . '</span>
                        </a>
                        </td>
                        <td class="display-added">' . $tr_total . ': ' . $price . ' ' . settings()->currency() . '</td>
                     </tr>
                      </table>';

            echo $display;

        } else {
            echo tr_('свободни стаи');
        }

    }

    public function count_added(Request $request, $id = null)
    {
        if ($id === null) {

            $added = $request->session->getData('added');
            print_r(array_sum(array_column($added, 'qty')));

        } else {

            $added = $request->session->getData('added.' . $id . '.qty');

            print_r($added);
        }

    }

    /**
     * ajax
     * @param Request $request
     */
    public function delete_added(Request $request)
    {
        $key = $request->post('id');

        $request->session->delete('added.' . $key);

        echo tr_('стаята е премахната');
    }

}
