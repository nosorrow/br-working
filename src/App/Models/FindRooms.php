<?php

namespace App\Models;

use Core\Model;

/**
 * Class FindRooms
 * @package App\Models
 */
class FindRooms extends Model
{
    /**
     * FindRooms constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getRoom($id)
    {
        $lang = get_cookie('lang');

        if (!$lang) {
            $lang = "bg_BG";
        }

        $sql = "SELECT tbl_room_type.id,
                tbl_room_type.room_type_slug,
                tbl_room_type_translate.beds,
                tbl_room_type.adults,
                tbl_room_type.child,
                tbl_room_type.price_weekday,
                tbl_room_type.price_weekend,
                tbl_room_type.quantity,
                tbl_room_type.img_type_url,
                tbl_room_type_translate.room_type,
                tbl_room_type_translate.description,
                tbl_room_type_translate.full_description
                FROM tbl_room_type
                LEFT JOIN tbl_room_type_translate
                ON tbl_room_type.id = tbl_room_type_translate.room_type_id
                WHERE tbl_room_type.id = ?
                AND  tbl_room_type_translate.lang_code= ?";

        $result = $this->execute_sql($sql, [$id, $lang])->fetch(\PDO::FETCH_ASSOC);

        $result['images'] = unserialize($result['img_type_url']);

        $sql_amenities = "SELECT tbl_amenities_translate.translate_name AS name,
                            tbl_amenities.icon,
                            tbl_amenities.icon_tag,
                            tbl_languages.lang_code AS lang_code
                            FROM `tbl_amenities`
                            JOIN `tbl_room_amenities`
                            ON tbl_amenities.id = tbl_room_amenities.amenitie_id
                            JOIN tbl_amenities_translate
                            ON tbl_amenities.id = tbl_amenities_translate.amenitie_id
                            JOIN tbl_languages
                            ON tbl_languages.lang_id = tbl_amenities_translate.lang_code_id
                            WHERE tbl_room_amenities.room_type_id = :room_type_id
                            AND lang_code = :lang_code";

        $sth = $this->dbh->prepare($sql_amenities);
        $sth->bindParam(':room_type_id', $id, \PDO::PARAM_INT);
        $sth->bindParam(':lang_code', $lang);
        $sth->execute();

        $result['amenities'] = $sth->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * @param $checkin
     * @param $checkout
     * @param $room_type_id
     * @return array
     */
    public function checkForAvailable($room_type_id, $checkin, $checkout, $reservartion_Id = '')
    {
        $checkUnavailable = $this->check_unavailable_room($room_type_id, $checkin, $checkout, $reservartion_Id);
        $unavailable = $checkUnavailable['unavailable'];
        $room_type = $this->getRoom($room_type_id);
        $available = $room_type['quantity'];

        return $available - $unavailable;
    }

    /**
     * @return array
     */
    public function roomTypes()
    {
        return $this->execute_sql('SELECT * FROM tbl_room_type')->fetchAll();
    }

    /**
     * @param $room_type_id
     * @param $checkin
     * @param $checkout
     * @param string $reservation_id
     * @return mixed
     */
    public function check_unavailable_room($room_type_id, $checkin, $checkout, $reservation_id = '')
    {
        $unavailable = "SELECT  COALESCE(SUM(tbl_reservation.qty), 0) AS unavailable
                        FROM `tbl_reservation`
                        WHERE `tbl_reservation`.room_type_id = :id
                        AND `tbl_reservation`.status != 'анулирана'
                        AND tbl_reservation.reservation_id != :reservation_id
                        AND (
                          (`checkin` >= :arrival   AND `checkin` <= :departure)
                          OR (`checkout` > :arrival  AND `checkout` <= :departure)
                          OR (`checkin` <= :arrival  AND `checkout` > :departure)
                        )";

        $stmt = $this->dbh->prepare($unavailable);
        $stmt->bindParam(':id', $room_type_id);
        $stmt->bindParam(':arrival', $checkin);
        $stmt->bindParam(':departure', $checkout);
        $stmt->bindParam(':reservation_id', $reservation_id);

        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * @param null $checkin
     * @param null $checkout
     * @return mixed
     */
    public function get_available_rooms($checkin = null, $checkout = null)
    {
        //all room id
        $id = [];
        foreach ($this->roomTypes() as $val) {
            $id[$val['id']] = $val['id'];
            // $id[] = $val['id'];
        }

        // loop for available qty
        foreach ($id as $key => $roomTypeId) {
            $unavailable = $this->check_unavailable_room($roomTypeId, $checkin, $checkout)['unavailable'];

            $result['room'][$key] = $this->getRoom($roomTypeId);

            if (($unavailable !== 0)) {
                $result['room'][$key]['available'] = $result['room'][$key]['quantity'] - $unavailable;

            } else {
                $result['room'][$key]['available'] = $result['room'][$key]['quantity'];
            }

            $result['room'][$key]['capacity'] = $result['room'][$key]['available'] * $result['room'][$key]['adults'];

            $_prices_array = $this->getPrice($checkin, $checkout, $roomTypeId);

            $price = array_sum($_prices_array) / count($_prices_array);

            $result['room'][$key]['total'] = array_sum($_prices_array);

            $result['room'][$key]['price'] = $price;

        }
        return $result;
    }

    public function get_available_room_names($checkin, $checkout, $room_type_id = '', $added_room_id = '')
    {
        //  Резултатът е id и името и типа на свободните стаи за даден период

        // масив със добавените стаи коитода не са включени в тръсенето
        if ($added_room_id != '' and is_array($added_room_id)) {
            $id = implode(' ,', $added_room_id);
            $added_room = " AND tbl_rooms.`id` NOT IN (" . $id . ") ";

        } elseif ($added_room_id != '') {
            $added_room = " AND tbl_rooms.`id` NOT IN (" . $added_room_id . ") ";

        } else {
            $added_room = '';
        }

        $sql = "SELECT tbl_rooms . `id` , tbl_rooms . `room_name`,
                tbl_rooms.room_type_id, tbl_room_type . room_type,
                tbl_room_type.adults, tbl_room_type.child
                FROM `tbl_rooms`
                JOIN tbl_room_type ON tbl_rooms . room_type_id = tbl_room_type . id
                WHERE tbl_room_type.id = :room_type_id
                AND tbl_rooms.`id` NOT IN
                (
                  SELECT room_id FROM tbl_reservation
                  WHERE NOT(`checkout` <= :checkin OR `checkin` >= :checkout)
                  AND `tbl_reservation`.status != 'анулирана'
                )
                {$added_room}
                ORDER BY tbl_room_type . `id`";

        return $this->execute_sql($sql, [':room_type_id' => $room_type_id,
                ':checkin' => $checkin,
                ':checkout' => $checkout]
        )->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @param $checkin
     * @param $checkout
     * @return mixed
     */
    public function getBookedRoomsForPeriod($checkin, $checkout, $id="", $reservation_id = "")
    {
        // връща id и името на стая ако е резервирана за периода

        // Ako е зададен резервационен код , ще изключи от резултата тази резервация.
        // Използвам при редакция на датите на дадена резервация
        if ($id){
            $roomId = " AND id = '" . (int)$id . "'";
        } else {
            $roomId = "";
        }

        if($reservation_id){
            $reservationId = " AND reservation_id != '". $reservation_id . "'";
        } else {
            $reservationId = "";
        }

        $sql = "SELECT `id` , `room_name` FROM `tbl_rooms`
                WHERE `id` IN (
                  SELECT room_id FROM tbl_reservation
                  WHERE NOT (`checkout` <= :checkin OR `checkin` >= :checkout) {$reservationId}
                ) {$roomId}";
        $result = $this->execute_sql($sql, [':checkout' => $checkout,
                                            ':checkin' => $checkin])->fetchAll(\PDO::FETCH_ASSOC);
        return $result;

    }

    /**
     * @param $checkin
     * @param $checkout
     * @param $id
     * @param $reservation_id
     * @return bool
     */
    public function checkIfRoomIsBooked($checkin, $checkout, $id, $reservation_id)
    {
        $result = $this->getBookedRoomsForPeriod($checkin, $checkout, $id, $reservation_id);

        // true ако е резервирана
        return (bool)count($result);
    }

    /**
     * @param $room_type_id
     * @return array
     */
    public function getAllRoomPrices($room_type_id)
    {
        $currency = settings()->currency();
        $sql = "SELECT CONCAT(tbl_room_type.price_weekday, ' {$currency}') as price_weekday ,
                CONCAT(tbl_room_type.price_weekend, ' {$currency}') as price_weekend
                FROM tbl_room_type WHERE tbl_room_type.id=?";

        $price['without_season'] = $this->execute_sql($sql, [$room_type_id])->fetch(\PDO::FETCH_ASSOC);


        $sql = "SELECT CONCAT(tbl_seasons.start_date, ' до ', tbl_seasons.end_date) AS period ,
                  CONCAT(tbl_prices.weekday, ' {$currency}') AS weekday,
                  CONCAT(tbl_prices.weekend, ' {$currency}') AS weekend
                  FROM tbl_prices
                  JOIN tbl_seasons ON tbl_prices.price_season_id = tbl_seasons.season_id
                  WHERE tbl_prices.price_room_type_id = ?";

        $price['seasons'] = $this->execute_sql($sql, [$room_type_id])->fetchAll(\PDO::FETCH_ASSOC);

        return $price;
    }

    /**
     * @param $date
     * @param $room_type_id
     * @return mixed
     */
    public function getSeasonPrice($date, $room_type_id)
    {
        $sql = "SELECT tbl_prices.weekday,  tbl_prices.weekend FROM tbl_prices
                JOIN tbl_seasons ON tbl_prices.price_season_id = tbl_seasons.season_id
                WHERE  tbl_seasons.start_date<= :now
                AND tbl_seasons.end_date >= :now
                AND tbl_prices.price_room_type_id = :room_type_id";

        $price = $this->execute_sql($sql, [':now' => $date, ':room_type_id' => $room_type_id])
            ->fetch(\PDO::FETCH_ASSOC);

        $day = new \DateTime($date);

        if (in_array($day->format('N'), $this->getDaySettings())) {

            return $price['weekend'];

        } else {

            return $price['weekday'];
        }

    }

    /**
     * @param $date
     * @param $roomtype_id
     * @return mixed
     */
    public function getRoomTypePrice($date, $roomtype_id)
    {
        $sql = "SELECT tbl_room_type.price_weekday, tbl_room_type.price_weekend
                FROM tbl_room_type WHERE tbl_room_type.id=?";

        $price = $this->execute_sql($sql, [$roomtype_id])->fetch(\PDO::FETCH_ASSOC);

        $day = new \DateTime($date);

        if (in_array($day->format('N'), $this->getDaySettings())) {

            return $price['price_weekend'];

        } else {

            return $price['price_weekday'];
        }

    }

    /**
     * @return array
     */
    public function getDaySettings()
    {
        $_array = $this->table('tbl_settings')->field('settings_value')
            ->where('settings_name', '=', 'basic')->getone();

        $array = (unserialize($_array->settings_value));

        return explode('-', $array['weekday']);
    }

    public function getPrice($checkin, $checkout, $room_type_id)
    {
        $prices = [];
        $start = new \DateTime($checkin);
        $end = new \DateTime($checkout);
        $interval = new \DateInterval('P1D');

        $period = new \DatePeriod($start, $interval, $end);

        foreach ($period as $day) {
            $_date = $day->format('Y-m-d');

            if ($this->getSeasonPrice($_date, $room_type_id)) {
                $prices[$_date] = $this->getSeasonPrice($_date, $room_type_id);

            } else {
                $prices[$_date] = $this->getRoomTypePrice($_date, $room_type_id);
            }

        }

        return $prices;
    }

    public function countRooms()
    {
        $sql = "SELECT SUM(quantity) AS rooms FROM tbl_room_type";
        return (int)$this->execute_sql($sql)->fetch(\PDO::FETCH_OBJ)->rooms;
    }

    /**
     * @return int
     */
    public function getMaxAdults()
    {
        $sql = 'SELECT MAX(`tbl_room_type`.`adults`) AS max FROM `tbl_room_type`';

        return (int)$this->execute_sql($sql)->fetch(\PDO::FETCH_OBJ)->max;
    }

    /**
     * @return int
     */
    public function countAdults()
    {
        $sql = 'SELECT SUM(`tbl_room_type`.`adults` * `tbl_room_type`.`quantity`) AS quests FROM `tbl_room_type`';

        return (int)$this->execute_sql($sql)->fetch(\PDO::FETCH_OBJ)->quests;
    }

    /**
     * @return int
     */
    public function countChild()
    {
        $sql = 'SELECT SUM(`tbl_room_type`.`child` * `tbl_room_type`.`quantity`) AS child FROM `tbl_room_type`';

        return (int)$this->execute_sql($sql)->fetch(\PDO::FETCH_OBJ)->child;
    }

    public function getRoomBySlug($slug)
    {
        $lang = get_cookie('lang');

        if (!$lang) {
            $lang = "bg_BG";
        }

        $sql = "SELECT tbl_room_type.id,
                tbl_room_type.room_type_slug,
                tbl_room_type_translate.beds,
                tbl_room_type.adults,
                tbl_room_type.child,
                tbl_room_type.price_weekday,
                tbl_room_type.price_weekend,
                tbl_room_type.quantity,
                tbl_room_type.img_type_url,
                tbl_room_type_translate.room_type,
                tbl_room_type_translate.description,
                tbl_room_type_translate.full_description
                FROM tbl_room_type
                LEFT JOIN tbl_room_type_translate
                ON tbl_room_type.id = tbl_room_type_translate.room_type_id
                WHERE tbl_room_type.room_type_slug = ?
                AND  tbl_room_type_translate.lang_code= ?";

        $result = $this->execute_sql($sql, [$slug, $lang])->fetch(\PDO::FETCH_ASSOC);

        $result['images'] = unserialize($result['img_type_url']);

        $sql_amenities = "SELECT tbl_amenities_translate.translate_name AS name,
                            tbl_amenities.icon,
                            tbl_amenities.icon_tag,
                            tbl_languages.lang_code AS lang_code
                            FROM `tbl_amenities`
                            JOIN `tbl_room_amenities`
                            ON tbl_amenities.id = tbl_room_amenities.amenitie_id
                            JOIN tbl_amenities_translate
                            ON tbl_amenities.id = tbl_amenities_translate.amenitie_id
                            JOIN tbl_languages
                            ON tbl_languages.lang_id = tbl_amenities_translate.lang_code_id
                            WHERE tbl_room_amenities.room_type_id = :room_type_id
                            AND lang_code = :lang_code";

        $sth = $this->dbh->prepare($sql_amenities);
        $sth->bindParam(':room_type_id', $result['id'], \PDO::PARAM_INT);
        $sth->bindParam(':lang_code', $lang);
        $sth->execute();

        $result['amenities'] = $sth->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

}