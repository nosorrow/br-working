/*SELECT
  tbl_reservation.reservation_id,
  tbl_clients.client_name,
  GROUP_CONCAT(tbl_rooms.room_name SEPARATOR ', ') AS room_names
  FROM tbl_reservation
  LEFT JOIN tbl_rooms ON tbl_reservation.room_id = tbl_rooms.id
  LEFT JOIN tbl_clients ON tbl_reservation.client_id = tbl_clients.id
  WHERE
  (DATE_FORMAT(NOW(),'%Y-%m-%d') = tbl_reservation.checkin)
GROUP BY tbl_reservation.reservation_id*/

/*SELECT
  tbl_reservation.reservation_id,
  tbl_clients.client_name,
  GROUP_CONCAT(tbl_rooms.room_name SEPARATOR ', ') AS room_names
FROM tbl_reservation
  LEFT JOIN tbl_rooms ON tbl_reservation.room_id = tbl_rooms.id
  LEFT JOIN tbl_clients ON tbl_reservation.client_id = tbl_clients.id
WHERE
  (DATE_FORMAT(NOW(),'%Y-%m-%d') = tbl_reservation.checkout)
GROUP BY tbl_reservation.reservation_id*/

/*select `br_booking`.`tbl_reservation`.`reservation_id` AS `reservation_id`,
`br_booking`.`tbl_reservation`.`checkin` AS `checkin`,
  `br_booking`.`tbl_reservation`.`checkout` AS `checkout`,
  `br_booking`.`tbl_reservation`.`created` AS `created`,
  `br_booking`.`tbl_reservation`.`status` AS `status`,
  `br_booking`.`tbl_clients`.`client_name` AS `client_name`,
  count(`br_booking`.`tbl_reservation`.`qty`) AS `qty`
from (`br_booking`.`tbl_reservation`
  left join `br_booking`.`tbl_clients`
    on((`br_booking`.`tbl_reservation`.`client_id` = `br_booking`.`tbl_clients`.`id`)))
where (`br_booking`.`tbl_reservation`.`status` <> 'new')
group by `br_booking`.`tbl_reservation`.`reservation_id`
order by `br_booking`.`tbl_reservation`.`created` desc*/

/*SELECT bg.translate_name as bg_name,
  en.translate_name as en_name
FROM tbl_amenities_translate as bg
RIGHT JOIN tbl_amenities_translate as en
  ON bg.amenitie_id = en.amenitie_id
WHERE bg.lang_code_id != en.lang_code_id AND bg.id<en.id*/

/*SELECT bg.translate_name as name, en.translate_name as en_name,
  tbl_amenities.icon,
  tbl_amenities.icon_tag
FROM tbl_amenities_translate as bg
  LEFT JOIN tbl_amenities ON tbl_amenities.id = bg.amenitie_id
  RIGHT JOIN tbl_amenities_translate as en
  ON bg.amenitie_id = en.amenitie_id
WHERE bg.lang_code_id != en.lang_code_id AND bg.id<en.id*/

/*SELECT tbl_room_type.id,
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
WHERE tbl_room_type.room_type_slug = 'edinichna-staya'
      AND bg.lang_code != en.lang_code AND bg.id<en.id*/

/*SELECT `id` , `room_name` FROM `tbl_rooms` WHERE `id` NOT IN (
  SELECT room_id FROM tbl_reservation
  WHERE NOT (`checkout` < '2017-07-25' OR `checkin` > '2017-07-20')
  AND id = 4
) ORDER BY `id`*/

SELECT tbl_room_type.`id` , tbl_room_type.`room_type`, tbl_reservation.qty
FROM `tbl_room_type`
  INNER JOIN tbl_reservation ON tbl_room_type.id = tbl_reservation.room_type_id
WHERE tbl_room_type.`id` IN (
  SELECT room_type_id FROM tbl_reservation
  WHERE NOT (`checkout` < '2017-06-05' OR `checkin` > '2017-06-11')
) ORDER BY `id`;