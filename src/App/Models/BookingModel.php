<?php

namespace App\Models;

use Core\Model;

class BookingModel extends Model
{
    /**
     * BookingModel constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $data
     * @return string
     */
    public function booking($data)
    {
        $created = date('Y-m-d H:i:s', time());
        $this->dbh->beginTransaction();

        try {

            $sql = "INSERT INTO tbl_reservation_comments (reservation_id, comment)
                    VALUES (:reservation_id, :reservation_comment)";

            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':reservation_id', $data['reservation_id'], \PDO::PARAM_STR);
            $sth->bindParam(':reservation_comment', $data['text'], \PDO::PARAM_STR);
            $sth->execute();


            $sql = "INSERT INTO tbl_clients (client_name, email, tel, reservation_unique_id)
                    VALUES (:client_name, :email, :telefon, :reservation_unique_id)";


            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':client_name', $data['name'], \PDO::PARAM_STR);
            $sth->bindParam(':email', $data['email'], \PDO::PARAM_STR);
            $sth->bindParam(':telefon', $data['telefon'], \PDO::PARAM_STR);
            $sth->bindParam(':reservation_unique_id', $data['reservation_id'], \PDO::PARAM_STR);
            $sth->execute();

            $client_id = $this->dbh->lastInsertId();

            foreach($data['added'] as $key => $added) {

                $data['room_type_id'] = $added['room_type_id'];
                $data['qty'] = $added['qty'];
                $data['adults'] = $added['adults'];
                $data['child'] = $added['child'];
                $data['total'] = $added['total'];

                if(isset($data['status'])){
                    //reservation status
                    $res_status = $data['status'];
                } else {
                    $res_status = "new";
                }

                if(isset($added['room_id'])){
                    //reservation status
                    $room_id = $added['room_id'];
                } else {
                    $room_id = 0;
                }

                $sql = "INSERT INTO tbl_reservation
                        (reservation_id, room_id, room_type_id, checkin, checkout, qty, adults, child,
                        created, price, res_code, status ,client_id)
                        VALUES (:reservation_id, :room_id, :room_type_id, :checkin, :checkout, :quantity, :adults, :child,
                            :created, :price, :res_code, :res_status, :client_id)";
                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':reservation_id', $data['reservation_id'], \PDO::PARAM_STR);
                $sth->bindParam(':room_id', $room_id, \PDO::PARAM_INT);
                $sth->bindParam(':room_type_id', $data['room_type_id'], \PDO::PARAM_INT);
                $sth->bindParam(':checkin', $data['checkin'], \PDO::PARAM_STR);
                $sth->bindParam(':checkout', $data['checkout'], \PDO::PARAM_STR);
                $sth->bindParam(':quantity', $data['qty'], \PDO::PARAM_INT);
                $sth->bindParam(':adults', $data['adults'], \PDO::PARAM_INT);
                $sth->bindParam(':child', $data['child'], \PDO::PARAM_INT);
                $sth->bindParam(':created', $created, \PDO::PARAM_STR);
                $sth->bindParam(':price', $data['total'], \PDO::PARAM_INT);
                $sth->bindParam(':res_code', $data['res_code'], \PDO::PARAM_STR);
                $sth->bindParam(':res_status', $res_status, \PDO::PARAM_STR);
                $sth->bindParam(':client_id', $client_id, \PDO::PARAM_INT);

                $sth->execute();
            }

            $st = $this->dbh->lastInsertId();

            $this->dbh->commit();

        } catch (\PDOException $e) {

            $this->dbh->rollBack();

            die ('Data base transaction error! Try again later!' . $e->getMessage());
        }

        return $st;
    }


    /**
     * @return array
     */
    public function getReservationRooms()
    {
        $sql = "SELECT roomtype, checkin, checkout FROM tbl_reservation ORDER BY roomtype";

        try {

            $sth = $this->dbh->prepare($sql);

            $sth->execute();

            $result = $sth->fetchAll();

            return $result;

        } catch (\Exception $e) {

            echo "! Some wrong: " . $e->getMessage() . " <br>in line: " . $e->getLine();

        }
    }

    /**
     * @param $id
     * @param $code
     * @return string
     */
    public function checkConfirmationLink($id, $code)
    {
        $sql = "SELECT COUNT(*) FROM tbl_reservation WHERE reservation_id = ? AND res_code = ? AND status = 'new'";

        return $this->execute_sql($sql, [$id, $code])->fetchColumn();
    }

    /**
     * @param $id
     * @param $code
     */
    public function confirm($id, $code)
    {
        $sql = "UPDATE tbl_reservation SET status='чакаща', res_code = '' WHERE reservation_id = ? AND res_code = ?";

        $this->execute_sql($sql, [$id, $code]);
    }

    /**
     * Garbage collection
     */
    public function deleteNotEmailConfirm($time)
    {
        $sql = "DELETE `tbl_reservation` ,`tbl_clients` FROM `tbl_reservation`
                JOIN `tbl_clients` ON tbl_reservation.client_id = tbl_clients.id
                WHERE `created` < ? AND `status` = 'new'";

        $this->execute_sql($sql, [$time]);
    }

}