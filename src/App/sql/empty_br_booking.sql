-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 18 авг 2017 в 18:50
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

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_reservation_comments`
--

CREATE TABLE `tbl_reservation_comments` (
  `comment_id` int(11) NOT NULL,
  `reservation_id` varchar(255) NOT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Структура на таблица `tbl_room_amenities`
--

CREATE TABLE `tbl_room_amenities` (
  `id` int(11) NOT NULL,
  `room_type_id` int(11) NOT NULL,
  `amenitie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

-- --------------------------------------------------------

--
-- Structure for view `tbl_datatables`
--
DROP TABLE IF EXISTS `tbl_datatables`;

CREATE VIEW `tbl_datatables`  AS  select `tbl_reservation`.`reservation_id` AS `reservation_id`,`tbl_reservation`.`checkin` AS `checkin`,`tbl_reservation`.`checkout` AS `checkout`,`tbl_reservation`.`created` AS `created`,`tbl_reservation`.`status` AS `status`,`tbl_clients`.`client_name` AS `client_name`,count(`tbl_reservation`.`qty`) AS `qty`,group_concat(`tbl_rooms`.`room_name` separator ', ') AS `room_names` from ((`tbl_reservation` left join `tbl_rooms` on((`tbl_reservation`.`room_id` = `tbl_rooms`.`id`))) left join `tbl_clients` on((`tbl_reservation`.`client_id` = `tbl_clients`.`id`))) where (`tbl_reservation`.`status` <> 'new') group by `tbl_reservation`.`reservation_id` order by `tbl_reservation`.`created` desc ;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_amenities_translate`
--
ALTER TABLE `tbl_amenities_translate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_clients`
--
ALTER TABLE `tbl_clients`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_languages`
--
ALTER TABLE `tbl_languages`
  MODIFY `lang_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_prices`
--
ALTER TABLE `tbl_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_reservation`
--
ALTER TABLE `tbl_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_reservation_comments`
--
ALTER TABLE `tbl_reservation_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_rooms`
--
ALTER TABLE `tbl_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_room_amenities`
--
ALTER TABLE `tbl_room_amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_room_type`
--
ALTER TABLE `tbl_room_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_room_type_translate`
--
ALTER TABLE `tbl_room_type_translate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_seasons`
--
ALTER TABLE `tbl_seasons`
  MODIFY `season_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
