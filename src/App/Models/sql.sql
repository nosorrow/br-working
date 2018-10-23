
/*To get occupied rooms for the period specified, i.e '2016-02-27'-'2016-02-24', you can use:

SELECT DISTINCT room_no
FROM reservation
WHERE check_in <= '2016-02-27' AND check_out >= '2016-02-24'
Output:

room_no
=======
13
14
To get available rooms you can use the previous query like this:

SELECT *
FROM room
WHERE room_no NOT IN (
  SELECT DISTINCT room_no
  FROM reservation
  WHERE check_in <= '2016-02-27' AND check_out >= '2016-02-24')
*/

/*
*  Резултатът е id и името и типа на свободните стаи за даден период
 */

SELECT tbl_rooms.`id` , tbl_rooms.`room_name`, tbl_room_type.room_type
FROM `tbl_rooms`
  JOIN tbl_room_type ON tbl_rooms.room_type_id = tbl_room_type.id
WHERE tbl_rooms.`id` NOT IN (
  SELECT room_id FROM tbl_reservation
  WHERE NOT (`checkout` < '2017-06-05' OR `checkin` > '2017-06-11')
) ORDER BY tbl_room_type.`id`;
/*
-- First --
SELECT `id` , `room_name` FROM `tbl_rooms` WHERE `id` NOT IN (
  SELECT room_id FROM tbl_reservation
  WHERE NOT (`checkout` < '2016-07-04' OR `checkin` > '2016-07-09')
) ORDER BY `id`;

-- Second --

SELECT `id` , `room_name` FROM `tbl_rooms` WHERE `id` NOT IN (
  SELECT room_id FROM `tbl_reservation`
  WHERE ('2016-07-04'<`checkout` AND '2016-07-09'>`checkin`)
) ORDER  BY `id`;
*/

/* Резервирани за период */
SELECT tbl_room_type.`id` , tbl_room_type.`room_type`, tbl_reservation.qty
FROM `tbl_room_type`
  INNER JOIN tbl_reservation ON tbl_room_type.id = tbl_reservation.room_type_id
WHERE tbl_room_type.`id` IN (
  SELECT room_type_id FROM tbl_reservation
  WHERE NOT (`checkout` < '2017-06-05' OR `checkin` > '2017-06-11')
) ORDER BY `id`;


/* ----------------------------- */

SELECT tbl_room_type.`id` , tbl_room_type.`room_type`, tbl_reservation.reservation_id, tbl_rooms.room_name
FROM `tbl_room_type`
  INNER JOIN tbl_reservation ON tbl_room_type.id = tbl_reservation.room_type_id
  INNER JOIN tbl_rooms ON tbl_reservation.room_id = tbl_rooms.id
WHERE tbl_room_type.`id` IN (
  SELECT room_type_id FROM tbl_reservation
  WHERE NOT (`checkout` < '2017-06-05' OR `checkin` > '2017-06-11')
) ORDER BY `id`;



/** връща резервираните стаи за периода и като result - брой оставащи */
SELECT tbl_room_type.`id` , tbl_room_type.`room_type`, tbl_room_type.units AS result
FROM `tbl_room_type`
WHERE tbl_room_type.`id` NOT IN (
  SELECT room_type_id FROM tbl_reservation
  WHERE NOT (`checkout` < :checkin OR `checkin` > :checkout)
)
UNION ALL
SELECT tbl_room_type.`id` , tbl_room_type.`room_type`, tbl_room_type.units - tbl_reservation.unit AS result
FROM `tbl_room_type`
  LEFT JOIN tbl_reservation ON tbl_room_type.id = tbl_reservation.room_type_id
WHERE tbl_room_type.`id` IN (
  SELECT room_type_id FROM tbl_reservation
  WHERE NOT (`checkout` < :checkin OR `checkin` > :checkout)
) GROUP BY tbl_room_type.id
HAVING result > 0
ORDER BY `id`;