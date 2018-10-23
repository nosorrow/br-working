<?php

namespace App\Models;

use Core\Model;

class DashboardResevations extends Model
{
    /**
     * BookingModel constructor.
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * @return array
     */
    public function fetch_all_reservations()
    {
        $sql = "
                SELECT GROUP_CONCAT(tbl_room_type.`room_type` SEPARATOR  ', ' ) as cc,
                tbl_reservation.`reservation_id`,tbl_reservation.`checkin`,tbl_reservation.`checkout`,
                tbl_reservation.`created`, tbl_reservation.`status`, tbl_clients.`client_name`,
                COUNT(tbl_reservation.`qty`) as qty
                FROM `tbl_reservation`
                LEFT JOIN tbl_clients ON tbl_reservation.`client_id` = tbl_clients.`id`
                JOIN tbl_room_type ON tbl_reservation.room_type_id = tbl_room_type.id
                GROUP BY tbl_reservation.`reservation_id`
                ORDER BY tbl_reservation.created DESC";

        return $this->execute_sql($sql)->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @param $id
     * @return array
     */
    public function fetch_reservation($id)
    {
        $sql = "SELECT
                tbl_reservation.id,
                tbl_reservation.`reservation_id`,
                tbl_reservation.`room_id`,
                tbl_rooms.room_name,
                tbl_reservation.`room_type_id`,
                tbl_reservation.`checkin`,
                tbl_reservation.`checkout`,
                tbl_reservation.`created`,
                tbl_reservation.`status`,
                tbl_reservation.adults,
                tbl_reservation.child,
                tbl_reservation.created,
                tbl_reservation_comments.comment,
                tbl_clients.id as client_id,
                tbl_clients.`client_name`,
                tbl_clients.email,
                tbl_clients.tel,
                tbl_room_type.room_type,
                tbl_reservation.price
                FROM `tbl_reservation`
                LEFT JOIN tbl_clients ON tbl_reservation.client_id=tbl_clients.id
                LEFT JOIN tbl_room_type ON tbl_reservation.room_type_id = tbl_room_type.id
                LEFT JOIN tbl_rooms ON tbl_reservation.room_id = tbl_rooms.id
                LEFT JOIN tbl_reservation_comments 
                  ON tbl_reservation.reservation_id = tbl_reservation_comments.reservation_id
                WHERE tbl_reservation.reservation_id = ?";

        return $this->execute_sql($sql, [$id])->fetchAll(\PDO::FETCH_OBJ);

    }

    /**
     * @return array
     */
    public function getcurrentReservations()
    {
        $sql = "SELECT tbl_reservation.reservation_id,
                GROUP_CONCAT(tbl_rooms.room_name SEPARATOR ', ') AS room_names
                FROM tbl_reservation
                LEFT JOIN tbl_rooms ON tbl_reservation.room_id = tbl_rooms.id
                WHERE
                (NOW() BETWEEN tbl_reservation.checkin AND tbl_reservation.checkout)
                AND tbl_reservation.status ='текуща'
                GROUP BY tbl_reservation.reservation_id";

        $stmt = $this->dbh->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * @return array
     */
    public function arrivalToday()
    {
        $sql = "SELECT
                tbl_reservation.reservation_id,
                tbl_clients.client_name,
                GROUP_CONCAT(tbl_rooms.room_name SEPARATOR ', ') AS room_names
                FROM tbl_reservation
                LEFT JOIN tbl_rooms ON tbl_reservation.room_id = tbl_rooms.id
                LEFT JOIN tbl_clients ON tbl_reservation.client_id = tbl_clients.id
                WHERE
                (DATE_FORMAT(NOW(),'%Y-%m-%d') = tbl_reservation.checkin)
                AND tbl_reservation.status !='текуща' AND tbl_reservation.status !='new'
                GROUP BY tbl_reservation.reservation_id";

        return $this->execute_sql($sql)->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @return array
     */
    public function departureToday()
    {
        $sql = "SELECT
                tbl_reservation.reservation_id,
                tbl_clients.client_name,
                GROUP_CONCAT(tbl_rooms.room_name SEPARATOR ', ') AS room_names
                FROM tbl_reservation
                LEFT JOIN tbl_rooms ON tbl_reservation.room_id = tbl_rooms.id
                LEFT JOIN tbl_clients ON tbl_reservation.client_id = tbl_clients.id
                WHERE
                (DATE_FORMAT(NOW(),'%Y-%m-%d') = tbl_reservation.checkout)
                AND tbl_reservation.status !='приключила'
                GROUP BY tbl_reservation.reservation_id";

        return $this->execute_sql($sql)->fetchAll(\PDO::FETCH_ASSOC);

    }

    /**
     * @return string
     */
    public function count_pending_reservations()
    {
        $sql = "SELECT tbl_reservation.reservation_id as pending
                FROM tbl_reservation WHERE tbl_reservation.status='чакаща'
                GROUP BY tbl_reservation.reservation_id";

        $result =  $this->execute_sql($sql)->fetchAll(\PDO::FETCH_ASSOC);

        return count($result);
    }

    /**
     * @return array
     */
    public function get_occupied_rooms()
    {
        $sql = "SELECT *
                FROM `tbl_reservation`
                WHERE tbl_reservation.status = 'текуща'";
        $result = $this->execute_sql($sql)->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

}