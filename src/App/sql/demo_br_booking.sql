-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 25 авг 2017 в 14:31
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
(10, 'душ', 'fa-shower', '<i class=\"fa fa-shower\" aria-hidden=\"true\"></i>');

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
(11, 'Plamen Petkov', 'plamenorama@gmail.com', '8888', 'SA564426'),
(12, 'Plamen Petkov', 'plamenorama@gmail.com', '55555555', 'XP115748'),
(13, 'Plamen Petkov', 'plamenorama@gmail.com', '887123456', 'EO309939'),
(14, 'Vikroria Petnova', 'plamenorama@gmail.com', '888888888', 'QM570592');

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
(49, 15, 50, 60, 1),
(50, 15, 60, 70, 2),
(51, 15, 100, 120, 3);

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
(136, 'SA564426', 1, 1, '2017-08-21 21:00:00', '2017-08-22 21:00:00', 1, 0, 1, '2017-08-22 19:37:52', 50, '', 'приключила', 11),
(138, 'SA564426', 2, 1, '2017-08-21 21:00:00', '2017-08-22 21:00:00', 1, 0, 1, '2017-08-22 19:45:18', 50, '', 'приключила', 11),
(139, 'XP115748', 4, 2, '2017-08-21 21:00:00', '2017-08-22 21:00:00', 2, 0, 1, '2017-08-22 19:46:31', 60, '', 'от служител', 12),
(140, 'XP115748', 5, 2, '2017-08-21 21:00:00', '2017-08-22 21:00:00', 1, 0, 1, '2017-08-22 19:46:31', 60, '', 'от служител', 12),
(141, 'EO309939', 1, 1, '2017-08-24 21:00:00', '2017-08-25 21:00:00', 1, 0, 1, '2017-08-24 17:31:34', 60, '', 'текуща', 13),
(142, 'EO309939', 7, 3, '2017-08-24 21:00:00', '2017-08-25 21:00:00', 2, 2, 1, '2017-08-24 17:31:34', 120, '', 'текуща', 13),
(144, 'QM570592', 4, 2, '2017-08-26 21:00:00', '2017-08-28 21:00:00', 2, 0, 1, '2017-08-24 17:38:09', 130, '', 'от служител', 14),
(145, 'QM570592', 1, 1, '2017-08-26 21:00:00', '2017-08-28 21:00:00', 1, 0, 1, '2017-08-24 17:38:40', 110, '', 'от служител', 14);

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
(9, 'ZV266273', 'Proba'),
(10, 'WI804270', ''),
(11, 'CZ433198', ''),
(12, 'YN205482', ''),
(13, 'TB399535', ''),
(14, 'LR214958', ''),
(15, 'KB889199', ''),
(16, 'ZF128823', ''),
(17, 'PV605141', ''),
(18, 'ON283336', '\';alert(String.fromCharCode(88,83,83))//\';alert(String.fromCharCode(88,83,83))//&quot;;\r\nalert(String.fromCharCode(88,83,83))//&quot;;alert(String.fromCharCode(88,83,83))//--\r\n&gt;&quot;&gt;'),
(19, 'PF396925', '\';alert(String.fromCharCode(88,83,83))//\';alert(String.fromCharCode(88,83,83))//&quot;;\r\nalert(String.fromCharCode(88,83,83))//&quot;;alert(String.fromCharCode(88,83,83))//--\r\n&gt;&quot;&gt;\'&gt;alert(String.fromCharCode(88,83,83))\r\n\'\';!--&quot;&lt;XSS&gt;=&amp;{()}\r\n\r\n\r\n&lt;IMG SRC=&quot;nojavascript...alert(\'XSS\');&quot;&gt;\r\n&lt;IMG SRC=nojavascript...alert(\'XSS\')&gt;\r\n&lt;IMG SRC=nojavascript...alert(\'XSS\')&gt;\r\n&lt;IMG SRC=nojavascript...alert(&quot;XSS&quot;)&gt;\r\n&lt;IMG SRC=`nojavascript...alert(&quot;RSnake says, \'XSS\'&quot;)`&gt;\r\n&lt;a &gt;xxs link&lt;/a&gt; \r\n&lt;a &gt;xxs link&lt;/a&gt; \r\n&lt;IMG &quot;&quot;&quot;&gt;alert(&quot;XSS&quot;)&quot;&gt;\r\n&lt;IMG SRC=nojavascript...alert(String.fromCharCode(88,83,83))&gt;\r\n&lt;IMG SRC=# &gt;\r\n&lt;IMG SRC= &gt;\r\n&lt;IMG &gt;\r\n&lt;IMG SRC=/ &gt;&lt;/img&gt;\r\n&lt;IMG SRC=nojavascript...alert(\r\n&amp;#39;XSS&amp;#39;)&gt;\r\n&lt;IMG SRC=nojavascript...a&amp;\r\n#0000108ert(&amp;#0000039;XSS&amp;#0000039;)&gt;\r\n&lt;IMG SRC=nojavascript...alert(&amp;#x27;XSS&amp;#x27;)&gt;\r\n&lt;IMG SRC=&quot;nojavascript...alert(\'XSS\');&quot;&gt;\r\n&lt;IMG SRC=&quot;nojavascript...alert(\'XSS\');&quot;&gt;\r\n&lt;IMG SRC=&quot;nojavascript...alert(\'XSS\');&quot;&gt;\r\n&lt;IMG SRC=&quot;nojavascript...alert(\'XSS\');&quot;&gt;\r\nperl -e \'print &quot;&lt;IMG SRC=java\\0script:alert(\\&quot;XSS\\&quot;)&gt;&quot;;\' &gt; out\r\n&lt;IMG SRC=&quot; &amp;#14;  javascript:alert(\'XSS\');&quot;&gt;\r\n\r\n&lt;BODY &gt;\r\n\r\n&lt;alert(&quot;XSS&quot;);//&lt;\r\n\r\n\r\n&lt;IMG SRC=&quot;nojavascript...alert(\'XSS\')&quot;\r\nalert(\'XSS\');\r\nalert(&quot;XSS&quot;);\r\n&lt;INPUT TYPE=&quot;IMAGE&quot; SRC=&quot;nojavascript...alert(\'XSS\');&quot;&gt;\r\n&lt;BODY BACKGROUND=&quot;nojavascript...alert(\'XSS\')&quot;&gt;\r\n&lt;IMG DYNSRC=&quot;nojavascript...alert(\'XSS\')&quot;&gt;\r\n&lt;IMG LOWSRC=&quot;nojavascript...alert(\'XSS\')&quot;&gt;\r\nli {list-style-image: url(&quot;javascript:alert(\'XSS\')&quot;);}&lt;UL&gt;&lt;LI&gt;XSS&lt;/br&gt;\r\n&lt;IMG SRC=\'novbscript...msgbox(&quot;XSS&quot;)\'&gt;\r\n&lt;IMG SRC=&quot;livescript:[code]&quot;&gt;\r\n&lt;BODY &gt;\r\n\r\n&lt;BR SIZE=&quot;&amp;{alert(\'XSS\')}&quot;&gt;\r\n\r\n\r\n@import\'http://ha.ckers.org/xss.css\';\r\n; REL=stylesheet&quot;&gt;\r\nBODY{-moz-binding:url(&quot;http://ha.ckers.org/xssmoz.xml#xss&quot;)}\r\n@im\\port\'\\ja\\vasc\\ript:alert(&quot;XSS&quot;)\';\r\n&lt;IMG STYLE=&quot;xss:expr/*XSS*/ession(alert(\'XSS\'))&quot;&gt;\r\nexp/*&lt;A STYLE=\'no\\xss:noxss(&quot;*//*&quot;);\r\nxss:ex/*XSS*//*/*/pression(alert(&quot;XSS&quot;))\'&gt;\r\nalert(\'XSS\');\r\n.XSS{background-image:url(&quot;javascript:alert(\'XSS\')&quot;);}&lt;A CLASS=XSS&gt;&lt;/A&gt;\r\nBODY{background:url(&quot;javascript:alert(\'XSS\')&quot;)}\r\nBODY{background:url(&quot;javascript:alert(\'XSS\')&quot;)} \r\n&lt;XSS &gt;\r\n&lt;XSS STYLE=&quot;behavior: url(xss.htc);&quot;&gt;\r\nВјscriptВѕalert(ВўXSSВў)Вј/scriptВѕ\r\n\r\n\r\n\r\n\r\n\r\n\r\n&lt;TABLE BACKGROUND=&quot;nojavascript...alert(\'XSS\')&quot;&gt;\r\n&lt;TABLE&gt;&lt;TD BACKGROUND=&quot;nojavascript...alert(\'XSS\')&quot;&gt;\r\n&lt;DIV &gt;\r\n&lt;DIV STYLE=&quot;background-image:\\0075\\0072\\006C\\0028\'\\006a\\0061\\0076\\0061\\0073\\0063\\0072\\0069\\0070\\0074\\003a\\0061\\006c\\0065\\0072\\0074\\0028.1027\\0058.1053\\0053\\0027\\0029\'\\0029&quot;&gt;\r\n&lt;DIV &gt;\r\n&lt;DIV &gt;\r\n&lt;!--[if gte IE 4]&gt;\r\n alert(\'XSS\');\r\n &lt;![endif]--&gt;\r\n\r\n \r\nEMBED SRC=&quot;http://ha.ckers.Using an EMBED tag you can embed a Flash movie that contains XSS. Click here for a demo. If you add the attributes allowScriptAccess=&quot;never&quot; and allownetworking=&quot;internal&quot; it can mitigate this risk (thank you to Jonathan Vanasco for the info).:\r\norg/xss.swf&quot; AllowScriptAccess=&quot;always&quot;&gt;\r\n\r\na=&quot;get&quot;;\r\nb=&quot;URL(\\&quot;&quot;;\r\nc=&quot;nojavascript...&quot;;\r\nd=&quot;alert(\'XSS\');\\&quot;)&quot;;\r\neval(a+b+c+d);\r\n&lt;I&gt;&lt;B&gt;&lt;IMG SRC=&quot;javas&lt;!-- --&gt;cript:alert(\'XSS\')&quot;&gt;&lt;/B&gt;&lt;/I&gt;\r\n&lt;SPAN DATASRC=&quot;#xss&quot; DATAFLD=&quot;B&quot; DATAFORMATAS=&quot;HTML&quot;&gt;&lt;/SPAN&gt;\r\n\r\n&lt;SPAN DATASRC=#I DATAFLD=C DATAFORMATAS=HTML&gt;&lt;/SPAN&gt;\r\n&lt;HTML&gt;&lt;BODY&gt;\r\n&lt;?xml:namespace prefix=&quot;t&quot; ns=&quot;urn:schemas-microsoft-com:time&quot;&gt;\r\n&lt;?import namespace=&quot;t&quot; implementation=&quot;#default#time2&quot;&gt;\r\nalert(&quot;XSS&quot;)&quot;&gt;\r\n&lt;/BODY&gt;&lt;/HTML&gt;\r\n\r\n&lt;!--#exec cmd=&quot;/bin/echo \'&lt;SCR\'&quot;--&gt;&lt;!--#exec cmd=&quot;/bin/echo \'IPT SRC=http://ha.ckers.org/xss.js&gt;\'&quot;--&gt;\r\n&lt;? echo(\'&lt;SCR)\';\r\necho(\'IPT&gt;alert(&quot;XSS&quot;)\'); ?&gt;\r\n&lt;IMG SRC=&quot;http://www.thesiteyouareon.com/somecommand.php?somevariables=maliciouscode&quot;&gt;\r\nRedirect 302 /a.jpg http://victimsite.com/admin.asp&amp;deleteuser\r\nalert(\'XSS\')&quot;&gt;\r\n&lt;HEAD&gt; &lt;/HEAD&gt;+ADw-SCRIPT+AD4-alert(\'XSS\');+ADw-/SCRIPT+AD4-\r\n&quot; SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;\r\n&quot; SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;\r\n&quot; \'\' SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;\r\n\'&quot; SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;\r\n` SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;\r\n\'&gt;&quot; SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;\r\ndocument.write(&quot;&lt;SCRI&quot;);PT SRC=&quot;http://ha.ckers.org/xss.js&quot;&gt;\r\n&lt;A HREF=&quot;http://66.102.7.147/&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;http://%77%77%77%2E%67%6F%6F%67%6C%65%2E%63%6F%6D&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;http://1113982867/&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;http://0x42.0x0000066.0x7.0x93/&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;http://0102.0146.0007.00000223/&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;h\r\ntt	p://6	6.000146.0x7.147/&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;//www.google.com/&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;//google&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;http://ha.ckers.org@google&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;http://google:ha.ckers.org&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;http://google.com/&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;http://www.google.com./&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;nojavascript...document.location=\'http://www.google.com/\'&quot;&gt;XSS&lt;/A&gt;\r\n&lt;A HREF=&quot;http://www.gohttp://www.google.com/ogle.com/&quot;&gt;XSS&lt;/A&gt;'),
(20, 'ON624358', 'rrrrrrrrrrrrrr'),
(21, 'SA564426', ''),
(22, 'XP115748', ''),
(23, 'EO309939', 'test'),
(24, 'QM570592', '');

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
(10, '303', '', 3);

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
(199, 2, 2),
(200, 2, 6),
(201, 2, 7),
(202, 2, 8),
(203, 2, 9),
(204, 3, 2),
(205, 3, 6),
(206, 3, 7),
(207, 3, 8),
(208, 3, 9),
(244, 1, 2),
(245, 1, 6),
(246, 1, 7),
(247, 1, 8),
(248, 1, 9),
(249, 1, 10);

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
(1, 'единична стая', 'edinichna-staya', 1, 0, 1, 45, 50, 3, 'a:1:{i:0;s:58:\"images/roomtype/edinichna-staya/2017082515-28-49_HINk8.jpg\";}'),
(2, 'двойна стая', 'dvojna-staya', 2, 2, 4, 60, 65, 3, 'a:1:{i:0;s:55:\"images/roomtype/dvojna-staya/2017082423-17-27_RN4FW.jpg\";}'),
(3, 'апартамент', 'apartament', 3, 3, 6, 100, 110, 3, 'a:1:{i:0;s:53:\"images/roomtype/apartament/2017082423-17-40_qZF92.jpg\";}');

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
(15, '2017-08-01', '2017-08-31', 'Летен');

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
(1, 'basic', 'a:9:{s:9:\"hotelname\";s:16:\"Villa Villekulla\";s:7:\"address\";s:19:\"Гладстон 45\";s:4:\"city\";s:10:\"Видин\";s:7:\"country\";s:16:\"България\";s:5:\"email\";s:15:\"pdpetkov@abv.bg\";s:5:\"phone\";s:14:\"+359 887112233\";s:3:\"web\";s:25:\"http://www.hotel-name.com\";s:8:\"currency\";s:5:\"лв.\";s:7:\"weekday\";s:5:\"5-6-7\";}');

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
(1, 'admin', '$2y$10$2v39d8EdYMfwvmHDsFlvZe161ovVMhkJQijfsWox91AV8o6umol3C', 'plamenorama@gmail.com', 'admin', 0, 1, 1503664105);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tbl_languages`
--
ALTER TABLE `tbl_languages`
  MODIFY `lang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_prices`
--
ALTER TABLE `tbl_prices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `tbl_reservation`
--
ALTER TABLE `tbl_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;
--
-- AUTO_INCREMENT for table `tbl_reservation_comments`
--
ALTER TABLE `tbl_reservation_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `tbl_rooms`
--
ALTER TABLE `tbl_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tbl_room_amenities`
--
ALTER TABLE `tbl_room_amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=250;
--
-- AUTO_INCREMENT for table `tbl_room_type`
--
ALTER TABLE `tbl_room_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_room_type_translate`
--
ALTER TABLE `tbl_room_type_translate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tbl_seasons`
--
ALTER TABLE `tbl_seasons`
  MODIFY `season_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
