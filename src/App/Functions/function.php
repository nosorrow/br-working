<?php

if (!function_exists('parseDates')) {

    /**
     * @param $date1
     * @param $date2
     * @return mixed
     */
    function parseDates($date1, $date2)
    {
        $start = new \DateTime($date1);

        $end = new \DateTime($date2);

        $interval = $start->diff($end);

        $tr_format = tr_('нощувка(и)');
        $interval = $interval->format('%a ' . $tr_format);

        $timestamp1 = strtotime($date1);

        $timestamp2 = strtotime($date2);

        $dateArrival = getdate($timestamp1);

        $dateDeparture = getdate($timestamp2);

        $bgArray = array('Ян.', 'Февр.', 'Март', 'Апр.', 'Май', 'Юни', 'Юли',
            'Авг.', 'Септ.', 'Окт.', 'Ноем.', 'Дек.');

        $enArray = array("January", "February", "March", "April",
            "May", "June", "July", "August", "September", "October", "November", "December");

        if (get_cookie('lang') == "bg_BG" || !get_cookie('lang')) {
            $_arrival = str_replace($enArray, $bgArray, $dateArrival);

            $_departure = str_replace($enArray, $bgArray, $dateDeparture);

        } else {
            $_arrival = $dateArrival;
            $_departure = $dateDeparture;
        }

        // не искам  -> Юли 20 - Юли 22 2016 / Юли 20 - 22 2016
        $_departure['month'] = ($_arrival['month']) == $_departure['month'] ? '' :$_departure['month'];

        // не искам в различни години -> дек. 28 - ян 5 2017 / дек. 28 2016 - ян 5 2017
        $_arrival['year'] = ($_arrival['year'] == $_departure['year']) ? '' :', ' . $_arrival['year'];

        $parse['dateStr'] = $_arrival['month'] . ' ' . ' ' . $_arrival['mday'] . ' ' . $_arrival['year'] . ' - '
            . $_departure['month'] . ' '
            . $_departure['mday'] . ', ' . $_departure['year'] . ' | ' . $interval;
        // return Юли 20 - Авг 22 2016 | 2 нощувки

        $parse['interval'] = (int)$interval;

        return $parse;
    }
}

if (!function_exists('reservation_code')) {
    /**
     * @param int $length
     * @return string
     */
    function reservation_code($length = 8)
    {
        $_code = openssl_random_pseudo_bytes(ceil($length / 2));

        $code = strtoupper(bin2hex($_code));

        return substr($code, 0, $length);
    }
}

if (!function_exists('generate_unique_id')) {
    /**
     * @param $n
     * @return string
     */
    function generate_unique_id($n)
    {
        $str = 'QWERTYUIOPLKJHGFDSAZXCVBNM';

        $id = substr(str_shuffle($str), 0, 2) . random_int(pow(10, ($n - 1)), pow(10, $n) - 1);

        return $id;
    }
}

if (!function_exists('amenities')) {
    /**
     * @return array
     */
    function amenities()
    {
        $model = new \App\Models\DashboardRoom();

        return $model->amenities();
    }
}

if (!function_exists('active_season')) {
    /**
     * @return mixed
     */
    function active_season()
    {
        $season = app(\App\Models\DashboardPrices::class);

        return $season->activeSeason();
    }

}

if (!function_exists('active_season_label')) {
    /**
     * @param $id
     * @return string
     */
    function active_season_label($id)
    {
        $season = app(\App\Models\DashboardPrices::class);

        if ($id == $season->activeSeason()->season_id) {
            return '<div class="label label-success" style="font-weight: normal">Активен</div>';
        } else {
            return "";
        }
    }

}
// Setting Functions ----------------

if (!function_exists('settings')) {
    /**
     * @return mixed
     */
    function settings()
    {

        $settings = app(\App\Models\SettingsModel::class);

        return $settings;
    }

}

//----------------------

if (!function_exists('room_status_color')) {
    /**
     * @param $data
     * @param $condition
     * @param $color
     * @return string
     */
    function room_status_color($data, $condition, $color)
    {
        $c = explode(':', $condition);
        $cy = $c[0];
        $cn = $c[1];

        $col = explode(":", $color);
        $coly = $col[0];
        $coln = $col[1];

        switch ($data) {
            case $cy:
                $tdcolor = $coly;
                break;
            case $cn:
                $tdcolor = $coln;
                break;
        }

        return 'style="color: #fff; background-color: ' . $tdcolor . ';"';
    }

}

if (!function_exists("passwordHash")) {
    /**
     * @param $str
     * @return bool|string
     */
    function passwordHash($str)
    {
        $opt = ["cost" => 10];
        return password_hash($str, PASSWORD_BCRYPT, $opt);
    }
}