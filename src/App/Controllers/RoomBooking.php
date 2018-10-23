<?php

namespace App\Controllers;

defined('APPLICATION_DIR') OR exit('No direct Accesss here !');
use Core\Controller;
use App\Models\FindRooms;
use App\Models\BookingModel;
use Core\Bootstrap\DiContainer;
use Core\Libs\Request;
use Core\Libs\Session;
use Core\Libs\Validator;
use Core\Libs\Csrf;

/**
 * /**
 * Class Rooms
 * @package App\Controllers
 */
class RoomBooking extends Controller
{
    public $findRooms;

    public $bookingModel;

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
    }

    public function index()
    {
        view('index');
    }

    public function display_added($add, $room_info)
    {
        $added = $add;
        $result = $room_info;

        if (count($added) > 0) {
            $price = 0;
            $qty = 0;
            $total_child = 0;
            $total_adults = 0;
            $display = '<table class="table table-striped table-hover" style="font-size: 14px">';
            $display .= '<td style="color: #777; font-weight: bold">' . sessionData('dateStr') . '</td>'.
                        '<td></td>';

            foreach ($added as $key => $value) {
                $child = (!$value['child']) ? '' :' | деца ' . $value['child'];
                $price = $price + $value['total'];
                $qty += $value['qty'];
                $total_child += $value['child'];
                $total_adults += $value['adults'];
                $display .= '<tr>
                            <td><strong>' . $value['qty'] . ' '. tr_('бр. от тип') . ': ' . $result[$value['r_k']]['room_type'] . '</strong></td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>'. tr_('възрастни') . ' ' . $value['adults'] . $child . '</td>
                            <td>'. tr_('цена') . ' '. $value['total'] . ' ' .settings()->currency() .'</td>
                            </tr>';

            }

            $display .= '<tr style="">
                        <td class="display-added">' . tn_('стая', 'стаи', $qty) . ': ' . $qty .' | '. tr_('възрастни') . ': ' . $total_adults . ' | ' .
                            tr_('деца'). ': ' . $total_child . '</td>
                        <td colspan="" class="display-added text-right" >'. tr_('общо') . ': ' . $price .' ' .settings()->currency() .' </td>
                     </tr>
                      </table>';
            return $display;

        } else {
            return tr_('Изберете стая за резервация');
        }

    }

    /**
     * @param Request $request
     * @param Validator $validator
     * @throws \Exception
     */
    public function booking(Request $request, Validator $validator)
    {
        $data['added'] = $request->session->getData('added');
        $room_info = $request->session->getData('result');
        $data['display'] = $this->display_added($data['added'], $room_info);

        $container = new DiContainer();
        $a = $container->get(Csrf::class);

        $data['errors'] = '';

        if (empty($data['added'])) {
            redirect(route('search'));

        } else {
            $data['checkin'] = $request->session->getData('checkin');
            $data['checkout'] = $request->session->getData('checkout');
            $data['dateStr'] = $request->session->getData('dateStr');
            $data['interval'] = $request->session->getData('interval');
            $data['reservation_id'] = generate_unique_id(6);
            $data['res_code'] = md5(reservation_code(8));

        }
        // ---- Validate form ----
        $phone = tr_('телефон');
        $comment = tr_('коментар');
        $validator->make('name', '(Име)', ['required', 'name', 'max:30'])->
        make('email', '(e-mail)', ['required', 'email', 'max:60'])->
        make('telefon', $phone, ['required', 'max:15', 'is_numeric'])->
        make('text', $comment, ['max:250']);

        if ($validator->run() === false) {
            $data['errors'] = $validator->errors('', '<li>', '</li>', '');
            $this->view->render('booking', $data);

        } else {
            if ($a->csrf_validate() === false) {
                $request->session->setFlash('error', '<div class="alert alert-danger" role="alert">!!! Oops - Wrong token !!!</div>');
                redirect(route('search'));

            } else {
                $data['name'] = $request->post('name');
                $data['email'] = $request->post('email');
                $data['telefon'] = $request->post('telefon');
                $data['text'] = $request->post('text');


                // дали някой е резервирал преди нас ?
                if ($this->check_for_available() === true) {
                    // Запис в БД
                    $this->bookingModel->booking($data);

                    $link = site_url('confirm?code=' . $data['res_code'] . '&rid=' . $data['reservation_id']);
                    $data['confirm_link'] = $link;

                    $address = $this->bookingModel->table('tbl_settings')
                                            ->where('settings_name', '=', 'basic')
                                            ->getone();

                    $data['address'] = unserialize($address->settings_value);

                    $request->session->setFlash('order', $data);

                    $formated = html_email($data);

                    $this->sendMail($data['email'], $formated);

                    redirect('order');

                } else {
                    $request->session->setFlash('error', '<div class="alert alert-danger" role="alert">
                                            Някоя от стаите току що бе резервирана от друг.
                                            Моля потърсете отново за свободни стаи!</div>');

                    redirect(route('search'));
                }
            }
        }

    }

    public function order()
    {
        $this->view->render('order');
    }

    public function confirm(Request $request, BookingModel $bookingModel)
    {
        $code = $request->get('code');
        $res_id = $request->get('rid');

        $msg = "Направена е нова резервация на " . date("Y-m-d H:i") . " ч.";

        if ($bookingModel->checkConfirmationLink($res_id, $code) > 0) {

            $bookingModel->confirm($res_id, $code);

            $this->sendMail(settings()->email, $msg);

            $msg = sprintf('<div class="alert alert-success" role="alert">%s</div>',
                    tr_('Резервацията е потвърдена'));

            $request->session->setFlash('msg', $msg);

            redirect(route('search', ['']));

        } else {
            $_trmsg = sprintf('<div class="alert alert-danger" role="alert">%s</div>', tr_('Изтекла валидност на кода за резервация'));
            $request->session->setFlash('error',$_trmsg);
            redirect(route('search', ['']));
        }
    }

    protected function check_for_available()
    {
        $s = $this->request->session->getData('added');
        $id = array_column($s, 'room_type_id');
        //брои добавените стаи по room_type_id
        $count = array_count_values($id);

        foreach ($count as $room_type_id => $added_qty) {
            $unavailable = $this->findRooms->checkForAvailable(
                $room_type_id,
                $this->request->session->getData('checkin'),
                $this->request->session->getData('checkout')
            );

            if ($unavailable < $added_qty) {

                return false;

            } else {

                return true;
            }
        }

    }

    public function ajaxCheck()
    {
        if ($this->check_for_available() === false) {

            echo 0;

        } else {

            echo 1;
        }
    }

    protected function sendMail($to, $message)
    {
        $mail = new \PHPMailer();
        $mail->CharSet = 'UTF-8';
        //From email address and name
        $mail->From = settings()->email;
        $mail->FromName = settings()->hotelname . ' ' . settings()->web;
        //To address and name
        $mail->addAddress($to);
        //Send HTML or Plain Text email
        $mail->isHTML(true);

        $mail->Subject = "Booking Order Info from " . settings()->web;

        $mail->Body = $message;

        if (!$mail->send()) {
            throw new \Exception("Грешка при изпращане на e-mail: " . $mail->ErrorInfo, 500);

        }

    }

}
