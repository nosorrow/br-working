<?php

function html_email(array $data= [])
{
    $reservation_id = $data['reservation_id'];
    $name = $data['name'];
    $confirm_link = $data['confirm_link'];
    $display = $data['display'];
    $hotel_name = $data['address']['hotelname'];
    $address = $data['address']['address'];
    $city = $data['address']['city'];
    $country = $data['address']['country'];
    $phone = $data['address']['phone'];
    $email = $data['address']['email'];
    $web = $data['address']['web'];

    $replacement = [
        $reservation_id,
        $name,
        $confirm_link,
        $display,
        $hotel_name,
        $address,
        $city,
        $country,
        $phone,
        $email,
        $web
    ];
    $search = [
        '{reservation_id}',
        '{name}',
        '{confirm_link}',
        '{display}',
        '{hotel_name}',
        '{address}',
        '{city}',
        '{country}',
        '{phone}',
        '{email}',
        '{web}'
    ];
    $file = VIEW_DIR . 'email/email.php';
    if(!file_exists($file)){
        throw new Exception('Липсва template за e-mail');
    }

    $html = file_get_contents($file);

    $replace = str_replace($search, $replacement, $html);

    return $replace;
}