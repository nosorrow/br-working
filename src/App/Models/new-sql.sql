# Връща броя резервирани за период
SELECT  COALESCE(SUM(tbl_reservation.qty), 0) AS unavailable
FROM `tbl_reservation`
WHERE (
  (`checkin` >= '2017-06-08' AND `checkin` <= '2017-06-09')
  OR (`checkout` > '2017-06-08' AND `checkout` <= '2017-06-09')
  OR (`checkin` <= '2017-06-08' AND `checkout` > '2017-06-09' )
);

#  Връща броя резервирани + инфо за тъпа стая за период
SELECT
  `tbl_room_type`.`id`,
  `tbl_room_type`.`room_type`,
  `tbl_room_type`.`room_type_slug`,
  `tbl_room_type`.`description` ,
  `tbl_room_type`.`full_description` ,
  `tbl_room_type`.adults,
  `tbl_room_type`.child,
  `tbl_room_type`.`max_guests`,
  `tbl_room_type`.`img_type_url`,
  `tbl_room_type`.`beds`,
  `tbl_room_type`.`price_weekday`,
  `tbl_room_type`.price_weekend,
  COALESCE(SUM(tbl_reservation.qty), 0) AS unavailable,
  COALESCE(SUM(tbl_reservation.qty), 0) * `tbl_room_type`.adults AS capacity
FROM `tbl_reservation`
  LEFT JOIN  tbl_room_type ON tbl_room_type.id = tbl_reservation.room_type_id
WHERE (
  (`checkin` >= '2017-06-08' AND `checkin` <= '2017-06-11')
  OR (`checkout` > '2017-06-08' AND `checkout` <= '2017-06-11')
  OR (`checkin` <= '2017-06-08' AND `checkout` > '2017-06-11' )
);
