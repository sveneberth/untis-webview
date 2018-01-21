-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 21. Jan 2018 um 03:04
-- Server-Version: 10.1.9-MariaDB
-- PHP-Version: 7.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `untis-view`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `untis_main`
--

DROP TABLE IF EXISTS `untis_main`;
CREATE TABLE IF NOT EXISTS `untis_main` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `untis_main`
--

INSERT INTO `untis_main` (`id`, `name`, `value`) VALUES
(1, 'school_name', 'GSG'),
(2, 'default_language', 'de'),
(4, 'meta_author', 'Xenux'),
(5, 'meta_desc', 'Hier die Beschreibung der Homepage, die in den Meta-Tags angezeigt wird'),
(6, 'meta_keys', 'Schl&uuml;sselw&ouml;rter der Homepage, die in den Meta-Tags angezeigt werden'),
(7, 'admin_email', 'admin@project'),
(8, 'users_can_register', 'true'),
(9, 'homepage_offline', 'false'),
(16, 'allowed_domains', '["gsgschule.de","gsg-do.de"]');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `untis_substitute_plan`
--

DROP TABLE IF EXISTS `untis_substitute_plan`;
CREATE TABLE IF NOT EXISTS `untis_substitute_plan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` text COLLATE utf8_unicode_ci COMMENT 'Datum',
  `hour` int(11) DEFAULT NULL COMMENT 'Stunde',
  `absent_teacher` text COLLATE utf8_unicode_ci COMMENT 'Absenter Lehrer',
  `substitute_teacher` text COLLATE utf8_unicode_ci COMMENT 'Vertretender Lehrer',
  `absent_subject` text COLLATE utf8_unicode_ci COMMENT 'Fach',
  `substitute_subject` text COLLATE utf8_unicode_ci COMMENT 'Vertretungsfach',
  `absent_room` text COLLATE utf8_unicode_ci COMMENT 'Raum',
  `substitute_room` text COLLATE utf8_unicode_ci COMMENT 'Vertretungsraum',
  `absent_class` text COLLATE utf8_unicode_ci COMMENT 'Klasse(n) mit ~ getrennt',
  `substitute_class` text COLLATE utf8_unicode_ci COMMENT 'Vertretungsklasse(n) mit ~ getrennt',
  `reason` text COLLATE utf8_unicode_ci COMMENT 'Absenzgrund',
  `kind` int(11) DEFAULT NULL COMMENT 'Art (Bitfeld)',
  `type` text COLLATE utf8_unicode_ci COMMENT 'Vertretungsart',
  `text` text COLLATE utf8_unicode_ci COMMENT 'Text zur Vertretung',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `untis_substitute_plan`
--

INSERT INTO `untis_substitute_plan` (`id`, `date`, `hour`, `absent_teacher`, `substitute_teacher`, `absent_subject`, `substitute_subject`, `absent_room`, `substitute_room`, `absent_class`, `substitute_class`, `reason`, `kind`, `type`, `text`) VALUES
(1, '20090806', 1, 'ZorB', '', 'BI', '', 'nBio', 'nBio', '["05A"]', '[""]', 'die', 16, 'L', ''),
(2, '20090806', 2, 'HanB', '', 'D', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'L', ''),
(3, '20090806', 3, 'LenM', '', 'EK', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'L', ''),
(4, '20090806', 4, 'LenM', '', 'EK', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'L', ''),
(5, '20090806', 5, 'HanB', '', 'GE', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'L', ''),
(6, '20090806', 6, 'KusS', '', 'E', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'L', ''),
(7, '20090807', 1, 'KusS', '', 'E', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'E', ''),
(8, '20090807', 2, 'KusS', '', 'E', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'E', ''),
(9, '20090807', 3, 'TepK', '', 'SP', '', 'TH2', '', '["05A"]', '[""]', 'die', 16, 'E', 'Ein besonders langer Text. Kann vorkommen, darf aber nichts kapput machen. Also stehe ich hier nur zum Testen.'),
(10, '20090807', 4, 'TepK', '', 'SP', '', 'TH2', '', '["05A"]', '[""]', 'die', 16, 'E', ''),
(11, '20090807', 5, 'HanB', '', 'VFG', '', 'n02', 'n02', '["05A"]', '[""]', 'die', 16, 'E', ''),
(12, '20090807', 5, 'BreM', '', 'VFG', '', 'n02', 'n02', '["05A"]', '[""]', 'die', 16, 'L', ''),
(13, '20090807', 6, 'HanB', '', 'D', '', 'n02', 'n02', '["05A"]', '[""]', 'die', 16, 'L', ''),
(14, '20090807', 1, 'ReuD', '', 'MU', '', 'nMu', 'nMu', '["05B"]', '[""]', 'die', 16, 'L', ''),
(15, '20090807', 2, 'ReuD', '', 'MU', '', 'nMu', 'nMu', '["05B"]', '[""]', 'die', 16, 'L', ''),
(16, '20090807', 4, 'KwaJ', '', 'M', '', 'n03', 'n03', '["05B"]', '[""]', 'die', 16, 'L', ''),
(17, '20090807', 5, 'BunK', '', 'D', '', 'n03', 'n03', '["05B"]', '[""]', 'die', 16, 'L', ''),
(18, '20090807', 6, 'BunK', '', 'VFG', '', 'n03', 'n03', '["05B"]', '[""]', 'die', 16, 'L', ''),
(19, '20090807', 6, 'KwaJ', '', 'VFG', '', 'n03', 'n03', '["05B"]', '[""]', 'die', 24, 'L', ''),
(20, '20090807', 1, 'LelH', '', 'E', '', 'n04', '', '["05C"]', '[""]', 'die', 16, 'L', ''),
(21, '20090807', 2, 'LelH', '', 'E', '', 'n04', '', '["05C"]', '[""]', 'die', 16, 'L', ''),
(22, '20090807', 3, 'LanA', '', 'SP', '', 'THGY', 'THGY', '["05C"]', '[""]', 'die', 16, 'L', ''),
(23, '20090807', 4, 'LanA', '', 'SP', '', 'THGY', 'THGY', '["05C"]', '[""]', 'die', 16, 'L', ''),
(24, '20090807', 5, 'PouM', '', 'GE', '', 'n04', '', '["05C"]', '[""]', 'die', 16, 'L', ''),
(25, '20090807', 6, 'PouM', '', 'GE', '', 'n04', '', '["05C"]', '[""]', 'die', 16, 'L', ''),
(26, '20090807', 1, 'AusC', '', 'D', '', 'n07', 'n07', '["05D"]', '[""]', 'die', 16, 'L', ''),
(27, '20090807', 2, 'BerJ', '', 'E', '', 'n07', 'n07', '["05D"]', '[""]', 'die', 16, 'L', ''),
(28, '20090807', 3, 'BerJ', '', 'E', '', 'n07', 'n07', '["05D"]', '[""]', 'die', 16, 'L', 'Aufgaben siehe Mail!'),
(29, '20090807', 4, 'BluH', '', 'M', '', 'n07', '', '["05D"]', '[""]', 'die', 16, 'L', ''),
(30, '20090807', 5, 'SchA', '', 'BI', '', 'nBio', 'nBio', '["05D"]', '[""]', 'die', 16, 'L', ''),
(31, '20090807', 6, 'SchA', '', 'BI', '', 'nBio', 'nBio', '["05D"]', '[""]', 'die', 24, 'L', ''),
(32, '20090807', 2, 'MatD', 'LelH', 'RELev', 'V', '138', '138', '["09B"]', '["09B"]', 'die', 0, '', ''),
(33, '20090807', 3, 'MatD', '', 're5', '', 'g25', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'die', 1, 'C', ''),
(34, '20090807', 3, 'ReuD', '', 'mu4', '', 'g07', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'die', 1, 'C', ''),
(35, '20090807', 3, 'GraK', '', 'mu2', '', 'nMu', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'die', 1, 'C', ''),
(36, '20090807', 4, 'GraK', '', 'mu2', '', 'nMu', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'die', 1, 'C', ''),
(37, '20090806', 1, 'BluH', '', 'M', '', 'n07', 'n07', '["05D"]', '[""]', 'die', 16, 'L', ''),
(38, '20090806', 2, 'BluH', '', 'M', '', 'n07', 'n07', '["05D"]', '[""]', 'die', 16, 'L', ''),
(39, '20090806', 9, 'KwaJ', '', 'sp5', '', 'THRS', '', '["QU 1\\/2","QU 3\\/4"]', '["QU 1\\/2","QU 3\\/4"]', 'KFL', 1, 'C', ''),
(40, '20090806', 10, 'KwaJ', '', 'sp5', '', 'THRS', '', '["QU 1\\/2","QU 3\\/4"]', '["QU 1\\/2","QU 3\\/4"]', 'KFL', 1, 'C', ''),
(41, '20090806', 9, 'KwaJ', '', 'sp2', '', 'TH', '', '["QU 1\\/2","QU 3\\/4"]', '["QU 1\\/2","QU 3\\/4"]', 'KFL', 1, 'C', ''),
(42, '20090806', 10, 'KwaJ', '', 'sp2', '', 'TH', '', '["QU 1\\/2","QU 3\\/4"]', '["QU 1\\/2","QU 3\\/4"]', 'KFL', 1, 'C', ''),
(43, '20090807', 2, 'BöhW', '', 'EK', '', '045', '045', '["08E"]', '[""]', 'du', 16, 'L', ''),
(44, '20090807', 3, 'BöhW', '', 'D', '', '155', '155', '["10D"]', '[""]', 'du', 16, 'L', ''),
(45, '20090807', 4, 'BöhW', '', 'D', '', '155', '155', '["10D"]', '[""]', 'du', 16, 'L', ''),
(46, '20090807', 6, 'BöhW', '', 'EK', '', 'g03', 'g03', '["08A"]', '[""]', 'du', 16, 'L', ''),
(47, '20090807', 7, 'BöhW', '', 'DE4', '', 'g18', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'du', 1, 'C', ''),
(48, '20090807', 8, 'BöhW', '', 'DE4', '', 'g18', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'du', 1, 'C', ''),
(49, '20090807', 7, 'BenH', '', 'FD', '', 'n02', 'n02', '["06A","06B","06C","06D","06E"]', '["06A","06B","06C","06D","06E"]', 'KFL', 0, '', ''),
(50, '20090806', 9, 'ReuD', '', 'Orchester', '', '085', '085', '["AG 5 - 13"]', '["AG 5 - 13"]', 'KFL', 0, '', ''),
(51, '20090806', 10, 'ReuD', '', 'Orchester', '', '085', '085', '["AG 5 - 13"]', '["AG 5 - 13"]', 'KFL', 0, '', ''),
(52, '20090807', 3, 'KraI', 'KraI', 'L1', 'L', '046', 'g18', '["07A","07B","07C","07D","07E"]', '["07A","07B","07C","07D","07E"]', '', 0, 'A', ''),
(53, '20090807', 6, 'HorC', '', 'D', '', '154', '', '["10C"]', '["10C"]', '', 1, 'C', ''),
(54, '20090807', 1, 'Fra', '', 'RELev2', '', '149', '', '["09A","09D"]', '["09A","09D"]', 'du', 1, 'C', ''),
(55, '20090807', 2, 'Fra', '', 'RELev2', '', '149', '', '["09A","09D"]', '["09A","09D"]', 'du', 1, 'C', ''),
(56, '20090807', 3, 'Fra', '', 'REL', '', 'g25', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'du', 1, 'C', ''),
(57, '20090807', 4, 'Fra', '', 'REL', '', 'g25', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'du', 1, 'C', ''),
(58, '20090806', 3, 'KwaJ', '', 'MA2', 'MA2', 'g17', 'g17', '["QU 1\\/2"]', '["QU 1\\/2"]', 'die', 0, '', 'Unterricht findet statt!'),
(59, '20090806', 3, 'SchrM', 'SchrM', 'BI', 'BI', '', '072', '["09E"]', '["09E"]', '', 0, 'R', ''),
(60, '20090806', 4, 'ZorB', 'ZorB', 'BI3', 'BI3', '072', 'g04', '["QU 1\\/2"]', '["QU 1\\/2"]', '', 0, 'R', ''),
(61, '20090806', 3, 'ZorB', 'ZorB', 'BI3', 'BI3', '072', 'g04', '["QU 1\\/2"]', '["QU 1\\/2"]', '', 0, 'R', ''),
(62, '20090806', 7, 'KusS', '', 'KU', '', '049', '', '["10C"]', '["10C"]', '', 1, 'C', ''),
(63, '20090806', 8, 'KusS', '', 'KU', '', '049', '', '["10C"]', '["10C"]', '', 1, 'C', ''),
(64, '20090807', 7, 'KwaJ', 'KwaJ', 'M', 'M', '045', '045', '["08E"]', '["08E"]', '', 0, 'A', 'Unterrichtsverlagerung bitte beachten!'),
(65, '20090807', 8, 'KwaJ', 'KwaJ', 'M', 'M', '045', '045', '["08E"]', '["08E"]', '', 0, 'A', 'Unterrichtsverlagerung bitte beachten!'),
(66, '20090807', 7, 'StoB', '', 'E', '', 'n05', '', '["06E"]', '["06E"]', '', 1, 'C', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `untis_users`
--

DROP TABLE IF EXISTS `untis_users`;
CREATE TABLE IF NOT EXISTS `untis_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` text COLLATE utf8_unicode_ci,
  `class` text COLLATE utf8_unicode_ci,
  `abbreviation` text COLLATE utf8_unicode_ci,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `last_confirmed` timestamp NULL DEFAULT NULL,
  `role` int(2) NOT NULL DEFAULT '1',
  `lastlogin_date` timestamp NULL DEFAULT NULL,
  `lastlogin_ip` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_fingerprint` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verifykey` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
