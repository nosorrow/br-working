<?php

namespace App\Models;

use Core\Model;

/**
 * Class DashboardPrices
 * @package App\Models
 */
class DashboardPrices extends Model
{
    /**
     * DashboardPrices constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function get_room_types_name()
    {
        return $this->table('tbl_room_type')->field('id', 'room_type')->get();
    }

    public function getSeasons()
    {
        return $this->table('tbl_seasons')->get();
    }

    public function getSeason($id)
    {
        return $this->table('tbl_seasons')->where('season_id', '=', $id)->getone(\PDO::FETCH_ASSOC);
    }

    public function activeSeason()
    {
        $now = date('Y-m-d');

        $sql = "SELECT * FROM tbl_seasons
                WHERE tbl_seasons.start_date<= ?
                AND tbl_seasons.end_date >= ?";

        $res =  $this->execute_sql($sql, [$now, $now])->fetchAll(\PDO::FETCH_OBJ);
        return $res[0];
    }

    public function getSeasonRoomTypePrice($season_id)
    {
        $array = [];
        $rooms = $this->get_room_types_name();

        $i = 0;
        foreach ($rooms as $room){
            $_prices = $this->table('tbl_prices')->field('weekday', 'weekend')
                            ->where([
                                ['price_season_id', '=', $season_id],
                                ['price_room_type_id' , '=', $room->id]
                            ])->getone();

            $array[$i]['room_type_id'] = $room->id;
            $array[$i]['room_type'] = $room->room_type;
            $array[$i]['price_weekday'] = $_prices->weekday;
            $array[$i]['price_weekend'] = $_prices->weekend;

            $i ++;
        }

        return $array;
//        return $this->table('tbl_prices')->where('price_season_id', '=', $season_id)->get();
    }

    public function insertNewSeasonPrices($new_season, $post)
    {
        $this->dbh->beginTransaction();

        try{

            $i = 0;

            $sql = "INSERT INTO tbl_seasons (start_date, end_date, season_name)
                    VALUES (:start_date, :end_date, :season_name)";
            extract($new_season);
            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':start_date', $start_date);
            $sth->bindParam(':end_date', $end_date);
            $sth->bindParam(':season_name', $season_name);
            $sth->execute();

            $new_season_id = $this->dbh->lastInsertId();
            $room_type = $this->get_room_types_name();

            foreach ($room_type as $key=>$roomType){
                $id = $roomType->id;

                $sql = "INSERT INTO tbl_prices (price_season_id, weekday, weekend, price_room_type_id)
                        VALUES (:price_season_id, :weekday, :weekend, :price_room_type_id)";

                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':price_season_id', $new_season_id, \PDO::PARAM_INT);
                $sth->bindParam(':weekday', $post['weekday_'.$id], \PDO::PARAM_INT);
                $sth->bindParam(':weekend', $post['weekend_'.$id], \PDO::PARAM_INT);
                $sth->bindParam(':price_room_type_id', $id, \PDO::PARAM_INT);

                $sth->execute();

                $i += $sth->rowCount();
            }

            $this->dbh->commit();

            return $i;

        } catch  (\PDOException $e) {

            $this->dbh->rollBack();

            die ('Data base transaction error! Try again later!' . $e->getMessage());
        }
    }

    /**
     * Има ли цена за тази стая ?
     * @param $id
     * @return mixed
     */
    public function checkRoomTypePriceId($id)
    {
        $sql = "SELECT COUNT(id) as count FROM tbl_prices WHERE price_room_type_id = ?";

        $result = $this->execute_sql($sql, [$id])->fetch(\PDO::FETCH_ASSOC);

        return $result['count'];
    }

    /**
     * @param $season
     * @param $post
     * @return int
     */
    public function updateNewSeasonPrices($season, $post)
    {
        $this->dbh->beginTransaction();

        try{
            $i = 0;
            $sql = "UPDATE tbl_seasons SET start_date =:start_date , end_date=:end_date,
                    season_name=:season_name WHERE season_id =:season_id";
            extract($season);

            $sth = $this->dbh->prepare($sql);
            $sth->bindParam(':season_id', $season_id);
            $sth->bindParam(':start_date', $start_date);
            $sth->bindParam(':end_date', $end_date);
            $sth->bindParam(':season_name', $season_name);
            $sth->execute();

            $i += $sth->rowCount();

            $room_type = $this->get_room_types_name();

            foreach ($room_type as $key=>$roomType){
                $id = $roomType->id;

                if($this->checkRoomTypePriceId($id)>0){
                    $sql = "UPDATE tbl_prices SET price_season_id=:price_season_id, weekday=:weekday,
                        weekend=:weekend, price_room_type_id=:price_room_type_id
                        WHERE price_season_id=:price_season_id
                        AND price_room_type_id=:price_room_type_id";

                } else {
                    $sql = "INSERT INTO tbl_prices (price_season_id, weekday, weekend, price_room_type_id)
                        VALUES (:price_season_id, :weekday, :weekend, :price_room_type_id)";
                }

                $sth = $this->dbh->prepare($sql);
                $sth->bindParam(':price_season_id', $season_id, \PDO::PARAM_INT);
                $sth->bindParam(':weekday', $post['weekday_'.$id], \PDO::PARAM_INT);
                $sth->bindParam(':weekend', $post['weekend_'.$id], \PDO::PARAM_INT);
                $sth->bindParam(':price_room_type_id', $id, \PDO::PARAM_INT);

                $sth->execute();

                $i += $sth->rowCount();
            }

            $this->dbh->commit();

            return $i;

        } catch  (\PDOException $e) {

            $this->dbh->rollBack();

            die ('Data base transaction error! Try again later!' . $e->getMessage());
        }
    }

}