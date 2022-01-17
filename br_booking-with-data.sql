-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 13 авг 2017 в 20:12
-- Версия на сървъра: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `br_booking`
--

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_amenities`
--

CREATE TABLE `tbl_amenities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `icon_tag` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_amenities`
--

INSERT INTO `tbl_amenities` (`id`, `name`, `icon`, `icon_tag`) VALUES
(2, 'климатик', 'fa-snowflake-o', '<i class=\"fa fa-snowflake-o\" aria-hidden=\"true\"></i>'),
(6, 'паркинг', 'fa-car', '<i class=\"fa fa-car\" aria-hidden=\"true\"></i>'),
(7, 'вана', 'fa-bath', '<i class=\"fa fa-bath\" aria-hidden=\"true\"></i>'),
(8, 'интернет', 'fa-wifi', '<i class=\"fa fa-wifi\" aria-hidden=\"true\"></i>'),
(9, 'закуска', 'fa-coffee', '<i class=\"fa fa-coffee\" aria-hidden=\"true\"></i>'),
(10, 'душ', 'fa fa-shower', '<i class=\"fa fa fa-shower\" aria-hidden=\"true\"></i>');

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_amenities_translate`
--

CREATE TABLE `tbl_amenities_translate` (
  `id` int(11) NOT NULL,
  `amenitie_id` int(11) NOT NULL,
  `lang_code_id` int(11) NOT NULL,
  `translate_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_amenities_translate`
--

INSERT INTO `tbl_amenities_translate` (`id`, `amenitie_id`, `lang_code_id`, `translate_name`) VALUES
(3, 2, 1, 'климатик'),
(4, 2, 2, 'air conditioner'),
(11, 6, 1, 'паркинг'),
(12, 6, 2, 'parking'),
(13, 7, 1, 'вана'),
(14, 7, 2, 'bathtub'),
(15, 8, 1, 'интернет'),
(16, 8, 2, 'wi-fi'),
(17, 9, 1, 'закуска'),
(18, 9, 2, 'breakfast'),
(19, 10, 1, 'душ'),
(20, 10, 2, 'shower');

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_clients`
--

CREATE TABLE `tbl_clients` (
  `id` int(10) UNSIGNED NOT NULL,
  `client_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `tel` varchar(50) NOT NULL,
  `reservation_unique_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_clients`
--

INSERT INTO `tbl_clients` (`id`, `client_name`, `email`, `tel`, `reservation_unique_id`) VALUES
(1, 'Plamen Petkov', 'plamenorama@gmail.com', '887331128', 'ZV266273');

-- --------------------------------------------------------

--
-- Stand-in structure for view `tbl_datatables`
-- (See below for the actual view)
--
CREATE TABLE `tbl_datatables` (
`reservation_id` varchar(255)
,`checkin` timestamp
,`checkout` timestamp
,`created` timestamp
,`status` varchar(20)
,`client_name` varchar(150)
,`qty` bigint(21)
,`room_names` text
);

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_languages`
--

CREATE TABLE `tbl_languages` (
  `lang_id` int(11) NOT NULL,
  `lang_code` varchar(10) NOT NULL,
  `language` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_languages`
--

INSERT INTO `tbl_languages` (`lang_id`, `lang_code`, `language`) VALUES
(1, 'bg_BG', 'bulgarian'),
(2, 'en_US', 'english');

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_prices`
--

CREATE TABLE `tbl_prices` (
  `id` int(11) NOT NULL,
  `price_season_id` int(11) NOT NULL,
  `weekday` int(11) NOT NULL,
  `weekend` int(11) NOT NULL,
  `price_room_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_prices`
--

INSERT INTO `tbl_prices` (`id`, `price_season_id`, `weekday`, `weekend`, `price_room_type_id`) VALUES
(42, 13, 50, 55, 1),
(43, 13, 60, 65, 2),
(44, 13, 110, 120, 3);

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_reservation`
--

CREATE TABLE `tbl_reservation` (
  `id` int(11) NOT NULL,
  `reservation_id` varchar(255) NOT NULL,
  `room_id` int(11) NOT NULL,
  `room_type_id` int(11) NOT NULL,
  `checkin` timestamp NULL DEFAULT NULL,
  `checkout` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `adults` int(11) NOT NULL,
  `child` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `price` int(11) NOT NULL,
  `res_code` varchar(50) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'new',
  `client_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_reservation`
--

INSERT INTO `tbl_reservation` (`id`, `reservation_id`, `room_id`, `room_type_id`, `checkin`, `checkout`, `adults`, `child`, `qty`, `created`, `price`, `res_code`, `status`, `client_id`) VALUES
(121, 'ZV266273', 4, 2, '2017-08-12 21:00:00', '2017-08-13 21:00:00', 2, 1, 1, '2017-08-13 17:37:40', 65, '', 'от служител', 1);

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_reservation_comments`
--

CREATE TABLE `tbl_reservation_comments` (
  `comment_id` int(11) NOT NULL,
  `reservation_id` varchar(255) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_reservation_comments`
--

INSERT INTO `tbl_reservation_comments` (`comment_id`, `reservation_id`, `comment`) VALUES
(1, 'YK554053', 'tova e new proba'),
(2, 'DI296111', 'New comment proba'),
(3, 'ZW379740', 'платена по банка'),
(4, 'ZA798778', 'tova e proba'),
(5, 'KZ824624', ''),
(6, 'WO293255', ''),
(7, 'VS589085', ''),
(8, 'ZG866620', 'proba'),
(9, 'ZV266273', 'Proba');

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_rooms`
--

CREATE TABLE `tbl_rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `room_status` varchar(255) NOT NULL,
  `room_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_rooms`
--

INSERT INTO `tbl_rooms` (`id`, `room_name`, `room_status`, `room_type_id`) VALUES
(1, '101', 'налична', 1),
(2, '102', 'налична', 1),
(3, '103', '', 1),
(4, '201', 'налична', 2),
(5, '202', 'налична', 2),
(6, '203', '', 2),
(7, '301', '', 3),
(8, '302', '', 3),
(9, '303', '', 3);

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_room_amenities`
--

CREATE TABLE `tbl_room_amenities` (
  `id` int(11) NOT NULL,
  `room_type_id` int(11) NOT NULL,
  `amenitie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_room_amenities`
--

INSERT INTO `tbl_room_amenities` (`id`, `room_type_id`, `amenitie_id`) VALUES
(17, 2, 2),
(18, 2, 6),
(19, 2, 7),
(20, 2, 8),
(21, 2, 9),
(159, 3, 2),
(160, 3, 6),
(161, 3, 7),
(162, 3, 8),
(163, 3, 9),
(164, 1, 2),
(165, 1, 6),
(166, 1, 8),
(167, 1, 9),
(168, 1, 10);

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_room_type`
--

CREATE TABLE `tbl_room_type` (
  `id` int(11) NOT NULL,
  `room_type` varchar(50) NOT NULL,
  `room_type_slug` varchar(255) NOT NULL,
  `adults` int(11) NOT NULL,
  `child` int(11) NOT NULL,
  `max_guests` int(11) NOT NULL DEFAULT '1',
  `price_weekday` int(11) NOT NULL,
  `price_weekend` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `img_type_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_room_type`
--

INSERT INTO `tbl_room_type` (`id`, `room_type`, `room_type_slug`, `adults`, `child`, `max_guests`, `price_weekday`, `price_weekend`, `quantity`, `img_type_url`) VALUES
(1, 'единична стая', 'edinichna-staya', 1, 0, 1, 45, 50, 3, 'a:1:{i:0;s:82:\"http://booking-room.dev/images/roomtype/edinichna-staya/2017080800-13-35_mIGAG.jpg\";}'),
(2, 'двойна стая', 'dvojna-staya', 2, 2, 4, 60, 65, 3, 'a:1:{i:0;s:71:\"http://booking-room.dev/images/roomtype/dvojna-staya/10_44_10_Vcyyd.jpg\";}'),
(3, 'апартамент', 'apartament', 3, 3, 6, 100, 110, 3, 'a:1:{i:0;s:69:\"http://booking-room.dev/images/roomtype/apartament/08_37_08_PUX7p.jpg\";}');

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_room_type_translate`
--

CREATE TABLE `tbl_room_type_translate` (
  `id` int(11) NOT NULL,
  `room_type_id` int(11) NOT NULL,
  `lang_code` varchar(10) NOT NULL,
  `room_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `full_description` text NOT NULL,
  `beds` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_room_type_translate`
--

INSERT INTO `tbl_room_type_translate` (`id`, `room_type_id`, `lang_code`, `room_type`, `description`, `full_description`, `beds`) VALUES
(1, 1, 'bg_BG', 'единична стая', '<p>Стандартните стаи са перфектната комбинация от функционалност и комфорт</p>', '<p>Стандартните стаи са перфектната комбинация от функционалност и комфортСтандартните стаи са перфектната комбинация от функционалност и комфорт</p>', '1 единично легло'),
(2, 1, 'en_US', 'single room', '<p>Our Standard Rooms are the perfect combination of function and comfort</p>', '<p>Our Standard Rooms are the perfect combination of function and comfort</p>\r\n\r\n<p>Our Standard Rooms are the perfect combination of function and comfort</p>', '1 single'),
(3, 2, 'bg_BG', 'двойна стая', '<p>Двойни стаи са перфектната комбинация от функционалност и комфорт</p>', '<p>Хотела разполага с общо 10 двойни стаи</p>\r\n\r\n<p>За Вашето удобство и комфорт, всички стаи разполагат с бани с вана и душ, сешоар, централна климатизация, мини-бар, LCD TV, кабелна телевизия, телефон, Wi-Fi.</p>', '2 единични'),
(4, 2, 'en_US', 'double room', '<p>Our Double Rooms are the perfect combination of function and comfort</p>', '<p>Our Standard Rooms are the perfect combination of function and comfort</p>\r\n\r\n<p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system,rt</p>', '2 singles beds'),
(5, 3, 'bg_BG', 'апартамент', '<p>Стандартният отрязък от Lorem Ipsum, използван от 1500 г. насам, е поместен по-долу за тези, които се </p>', '<p>Стандартният отрязък от Lorem Ipsum, използван от 1500 г. насам, е поместен по-долу за тези, които се интересуват. Секции 1.10.32 и 1.10.33 от \"de Finibus Bonorum et Malorum\" на Цицерон също са поместени в оригиналния им формат, заедно с превода им на английски език, направен от H. Rackham през 1914г.</p>', '2 спални 2 единични'),
(6, 3, 'en_US', 'apartment', '<p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give </p>', '<p>But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, </p>', '2 king size 2 single bed');

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_seasons`
--

CREATE TABLE `tbl_seasons` (
  `season_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `season_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_seasons`
--

INSERT INTO `tbl_seasons` (`season_id`, `start_date`, `end_date`, `season_name`) VALUES
(13, '2017-07-01', '2017-08-31', 'Летен-силен');

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_session`
--

CREATE TABLE `tbl_session` (
  `Session_Id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Session_Expires` datetime NOT NULL,
  `Session_Data` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `settings_name` varchar(255) NOT NULL,
  `settings_value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `settings_name`, `settings_value`) VALUES
(1, 'basic', 'a:9:{s:9:\"hotelname\";s:17:\"Hotel Star Palace\";s:7:\"address\";s:19:\"Гладстон 45\";s:4:\"city\";s:10:\"Видин\";s:7:\"country\";s:16:\"България\";s:5:\"email\";s:15:\"pdpetkov@abv.bg\";s:5:\"phone\";s:14:\"+359 887112233\";s:3:\"web\";s:25:\"http://www.hotel-name.com\";s:8:\"currency\";s:5:\"лв.\";s:7:\"weekday\";s:5:\"5-6-7\";}');

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `user` varchar(20) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'admin',
  `ban` int(2) NOT NULL DEFAULT '0',
  `isonline` int(1) NOT NULL,
  `timefrom` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Схема на данните от таблица `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `user`, `pass`, `email`, `type`, `ban`, `isonline`, `timefrom`) VALUES
(1, 'admin', '$2y$10$2v39d8EdYMfwvmHDsFlvZe161ovVMhkJQijfsWox91AV8o6umol3C', 'plamenorama@gmail.com', 'admin', 0, 1, 1502642427);

-- --------------------------------------------------------

--
-- Structure for view `tbl_datatables`
--
DROP TABLE IF EXISTS `tbl_datatables`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tbl_datatables`  AS  select `tbl_reservation`.`reservation_id` AS `reservation_id`,`tbl_reservation`.`checkin` AS `checkin`,`tbl_reservation`.`checkout` AS `checkout`,`tbl_reservation`.`created` AS `created`,`tbl_reservation`.`status` AS `status`,`tbl_clients`.`client_name` AS `client_name`,count(`tbl_reservation`.`qty`) AS `qty`,group_concat(`tbl_rooms`.`room_name` separator ', ') AS `room_names` from ((`tbl_reservation` left join `tbl_rooms` on((`tbl_reservation`.`room_id` = `tbl_rooms`.`id`))) left join `tbl_clients` on((`tbl_reservation`.`client_id` = `tbl_clients`.`id`))) where (`tbl_reservation`.`status` <> 'new') group by `tbl_reservation`.`reservation_id` order by `tbl_reservation`.`created` desc ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_amenities`
--
ALTER TABLE `tbl_amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_amenities_translate`
--
ALTER TABLE `tbl_amenities_translate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_clients`
--
ALTER TABLE `tbl_clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reservation_unique_id` (`reservation_unique_id`);

--
-- Indexes for table `tbl_languages`
--
ALTER TABLE `tbl_languages`
  ADD PRIMARY KEY (`lang_id`);

--
-- Indexes for table `tbl_prices`
--
ALTER TABLE `tbl_prices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_reservation`
--
ALTER TABLE `tbl_reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `tbl_reservation_comments`
--
ALTER TABLE `tbl_reservation_comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `tbl_rooms`
--
ALTER TABLE `tbl_rooms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_room_amenities`
--
ALTER TABLE `tbl_room_amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_room_type`
--
ALTER TABLE `tbl_room_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_room_type_translate`
--
ALTER TABLE `tbl_room_type_translate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_seasons`
--
ALTER TABLE `tbl_seasons`
  ADD PRIMARY KEY (`season_id`);

--
-- Indexes for table `tbl_session`
--
ALTER TABLE `tbl_session`
  ADD PRIMARY KEY (`Session_Id`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_amenities`
--
ALTER TABLE `tbl_amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tbl_amenities_translate`
--
ALTER TABLE `tbl_amenities_translate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `tbl_clients`
--
ALTER TABLE `tbl_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_languages`
--
ALTER TABLE `tbl_languages`
  MODIFY `lang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_prices`
--
ALTER TABLE `tbl_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- AUTO_INCREMENT for table `tbl_reservation`
--
ALTER TABLE `tbl_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;
--
-- AUTO_INCREMENT for table `tbl_reservation_comments`
--
ALTER TABLE `tbl_reservation_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tbl_rooms`
--
ALTER TABLE `tbl_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `tbl_room_amenities`
--
ALTER TABLE `tbl_room_amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=169;
--
-- AUTO_INCREMENT for table `tbl_room_type`
--
ALTER TABLE `tbl_room_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbl_room_type_translate`
--
ALTER TABLE `tbl_room_type_translate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tbl_seasons`
--
ALTER TABLE `tbl_seasons`
  MODIFY `season_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
