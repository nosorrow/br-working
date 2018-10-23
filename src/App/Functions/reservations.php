<?php
/**
 * @return string
 */
function drop_down_rooms($checkin = '', $checkout = '', $added_rooms_id='')
{
    $model = new \App\Models\DashboardRoom();
    $findModel = new App\Models\FindRooms();
    $types = $model->getAllRoomTypes();

    $output = '<option value="">--изберете стая--</option>';

    foreach ($types as $roomType) {
        $available_rooms = $findModel->get_available_room_names($checkin, $checkout, $roomType->id, $added_rooms_id);
        if($available_rooms){
            $output .= '<optgroup label="' . $roomType->room_type . '">';
        }

        foreach ($available_rooms as $room) {
            $output .= '<option data-roomtypeid="'.  $room['room_type_id'] .
                '" value="' . $room['id'] . '">' . $room['room_name'] . '</option>';
        }
        $output .= '</optgroup>';
    }

    return $output;

}

/**
 * @param $checkin
 * @param $checkout
 * @param $roomType
 * @return mixed
 */
function calculate_price($checkin, $checkout, $roomType)
{
    $obj = new App\Models\FindRooms();

    $_prices_array = $obj->getPrice($checkin, $checkout, $roomType);

    $price = array_sum($_prices_array) / count($_prices_array);

    $result['total'] = array_sum($_prices_array);

    $result['price'] = $price;

    return $result;

}

if (!function_exists('status')) {

// Format Reservation Status;
    function status($item)
    {
        switch ($item) {
            case 'анулирана':
                $s['label'] = ' label-default';
                break;
            case 'чакаща':
                $s['label'] = ' label-danger';
                break;
            case 'приета':
                $s['label'] = ' label-info';
                break;
            case 'потвърдена':
                $s['label'] = ' label-success';
                break;
            case 'текуща':
                $s['label'] = ' label-warning';
                break;
            case 'приключила':
                $s['label'] = ' label-end';
                break;
            case 'от служител':
                $s['label'] = ' label-primary';
                break;

        }
        return (object)$s;
    }
}