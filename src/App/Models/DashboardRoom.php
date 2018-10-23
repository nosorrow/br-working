<?php

namespace App\Models;

use Core\Model;

/**
 * Class DashboardRoom
 * @package App\Models
 */
class DashboardRoom extends Model
{
    /**
     * DashboardRoom constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     */
    public function getAllRoomTypes()
    {
        $sql = "SELECT tbl_room_type.id, tbl_room_type.room_type,
                tbl_room_type.room_type_slug, tbl_room_type.adults, tbl_room_type.child,
                tbl_room_type_translate.beds, tbl_room_type_translate.description,
                tbl_room_type.max_guests, tbl_room_type.price_weekday, tbl_room_type.price_weekend,
                tbl_room_type.quantity, tbl_room_type.img_type_url
                FROM tbl_room_type
                INNER JOIN tbl_room_type_translate ON tbl_room_type.id = tbl_room_type_translate.room_type_id
                GROUP BY tbl_room_type.id";

        return $this->execute_sql($sql)->fetchAll(\PDO::FETCH_OBJ);
    }

    public function getRoomsByType($room_type_id)
    {
        $sql = "SELECT tbl_rooms.id, tbl_rooms.room_name
                FROM tbl_rooms
                WHERE tbl_rooms.room_type_id = :room_type_id";

        return $this->execute_sql($sql, [':room_type_id'=>$room_type_id])->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param $room_type_id
     * @return mixed
     */
    public function countRoomByTypes($room_type_id)
    {
        $sql = "SELECT COUNT(*) as room_qty FROM tbl_rooms WHERE room_type_id = :id";

        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $room_type_id, \PDO::PARAM_INT);
        $stmt->execute();
        $result =  $stmt->fetch(\PDO::FETCH_OBJ);
        return $result->room_qty;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function roomTypesQty($id){
        $result = $this->table('tbl_room_type')->field('quantity')
                    ->where('id', '=', $id)->getone();

        return $result->quantity;
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getId($slug)
    {
        $id = $this->table('tbl_room_type')->where('room_type_slug', '=', $slug)->getOne()->id;

        return $id;
    }

    /**
     * Връща инфо за RoomType
     * @param $slug
     * @return mixed
     */
    public function getRoomType($slug)
    {
        $sql = "SELECT tbl_room_type.id,
                  bg.room_type,
                  en.room_type as room_type_en,
                  tbl_room_type.room_type_slug,
                  bg.description,
                  bg.full_description,
                  en.description as description_en,
                  en.full_description as full_description_en,
                  bg.beds, en.beds as beds_en,
                  tbl_room_type.adults,
                  tbl_room_type.child,
                  tbl_room_type.max_guests,
                  tbl_room_type.price_weekday,
                  tbl_room_type.price_weekend,
                  tbl_room_type.quantity,
                  tbl_room_type.img_type_url
                FROM tbl_room_type_translate as bg
                JOIN tbl_room_type
                    ON tbl_room_type.id = bg.room_type_id
                JOIN tbl_room_type_translate as en
                  ON en.room_type_id = bg.room_type_id
                WHERE tbl_room_type.room_type_slug = :room_type_slug
                AND bg.lang_code != en.lang_code AND bg.id<en.id";

        $result = $this->execute_sql($sql, [':room_type_slug'=>$slug])->fetch(\PDO::FETCH_ASSOC);
        return $result;

    }

    /**
     * @return array
     */
    public function amenities()
    {
        return $this->table('tbl_amenities')->get(\PDO::FETCH_ASSOC);
    }

    public function fetch_amenities_model()
    {
        $sql_1 = "SELECT bg.amenitie_id as id,
                    bg.translate_name as name, en.translate_name as en_name,
                    tbl_amenities.icon,
                    tbl_amenities.icon_tag
                    FROM tbl_amenities_translate as bg
                    LEFT JOIN tbl_amenities ON tbl_amenities.id = bg.amenitie_id
                    RIGHT JOIN tbl_amenities_translate as en
                    ON bg.amenitie_id = en.amenitie_id
                    WHERE bg.lang_code_id != en.lang_code_id AND bg.id<en.id";

        $amenities = $this->execute_sql($sql_1)->fetchAll(\PDO::FETCH_OBJ);

        return $amenities;
    }

    /**
     * @param $id
     * @return array
     */
    public function amenitiesForRoom($id)
    {
        $result = $this->table('tbl_room_amenities')
            ->field('amenitie_id')
            ->where('room_type_id', '=', $id)->get(\PDO::FETCH_ASSOC);
        foreach ($result as $k => $v) {
            $amenitie_id[] = $v['amenitie_id'];
        }
        return $amenitie_id;
    }

    /**
     * @param $room_id
     * @return int
     */
    public function delete_room_amenities($room_id)
    {
        $del = $this->table('tbl_room_amenities')->where('room_type_id', '=', $room_id)->delete();

        return $del;
    }

    /**
     * @return FindRooms
     */
    public function capacity()
    {
        return new FindRooms();
    }

    /**
     * @param string $order
     * @return array
     */
    public function getRoomNames($order = "name")
    {
        if ($order != "name" || $order !="type"){
            $order = "name";
        }

        $sql = "SELECT tbl_rooms.id, tbl_rooms.room_name AS name,
                tbl_room_type.room_type AS type,
                tbl_rooms.room_status as room_status
                FROM tbl_rooms
                JOIN tbl_room_type ON tbl_rooms.room_type_id = tbl_room_type.id
                ORDER BY " . $order . " ASC";

        $stmt = $this->dbh->prepare($sql);

        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function roomGetTypeId($roomId)
    {
        return $this->table('tbl_rooms')->field('room_type_id')
            ->where('id', '=', $roomId)->getone();
    }

    public function roomGetInfo($roomId)
    {
        $sql = "SELECT tbl_rooms.id, tbl_rooms.room_name,
                tbl_rooms.room_type_id,
                tbl_room_type.adults,
                tbl_room_type.child
                FROM tbl_rooms
                LEFT JOIN tbl_room_type ON tbl_rooms.room_type_id = tbl_room_type.id
                WHERE tbl_rooms.id = :roomId";

        return $this->execute_sql($sql, [':roomId'=>$roomId])->fetch(\PDO::FETCH_ASSOC);
    }
}