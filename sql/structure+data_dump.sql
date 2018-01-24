-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 24. Jan 2018 um 16:42
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
CREATE TABLE `untis_main` (
  `id` int(10) NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `untis_main`
--

INSERT INTO `untis_main` (`id`, `name`, `value`) VALUES
(1, 'school_name', 'GSG'),
(2, 'default_language', 'de'),
(4, 'meta_author', 'Xenux'),
(5, 'meta_desc', 'Hier die Beschreibung der Homepage, die in den Meta-Tags angezeigt wird'),
(6, 'meta_keys', 'Schl&uuml;sselw&ouml;rter der Homepage, die in den Meta-Tags angezeigt werden'),
(7, 'admin_email', 'se@firemail.cc'),
(8, 'users_can_register', 'true'),
(9, 'homepage_offline', 'false'),
(16, 'allowed_domains', '["gsgschule.de","gsg-do.de","gmx.de"]'),
(17, 'confirmValidity', '365');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `untis_substitute_plan`
--

DROP TABLE IF EXISTS `untis_substitute_plan`;
CREATE TABLE `untis_substitute_plan` (
  `id` int(11) NOT NULL,
  `date` date DEFAULT NULL COMMENT 'Datum',
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
  `text` text COLLATE utf8_unicode_ci COMMENT 'Text zur Vertretung'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `untis_substitute_plan`
--

INSERT INTO `untis_substitute_plan` (`id`, `date`, `hour`, `absent_teacher`, `substitute_teacher`, `absent_subject`, `substitute_subject`, `absent_room`, `substitute_room`, `absent_class`, `substitute_class`, `reason`, `kind`, `type`, `text`) VALUES
(1, '2018-03-06', 1, 'ZorB', '', 'BI', '', 'nBio', 'nBio', '["05A"]', '[""]', 'die', 16, 'L', ''),
(2, '2018-03-06', 2, 'HanB', '', 'D', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'L', ''),
(3, '2018-03-06', 3, 'LenM', '', 'EK', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'L', ''),
(4, '2018-03-06', 4, 'LenM', '', 'EK', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'L', ''),
(5, '2018-03-06', 5, 'HanB', '', 'GE', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'L', ''),
(6, '2018-03-06', 6, 'KusS', '', 'E', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'L', ''),
(7, '2018-03-07', 1, 'KusS', '', 'E', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'E', ''),
(8, '2018-03-07', 2, 'KusS', '', 'E', '', 'n02', '', '["05A"]', '[""]', 'die', 16, 'E', ''),
(9, '2018-03-07', 3, 'TepK', '', 'SP', '', 'TH2', '', '["05A"]', '[""]', 'die', 16, 'E', 'Ein besonders langer Text. Kann vorkommen, darf aber nichts kapput machen. Also stehe ich hier nur zum Testen.'),
(10, '2018-03-07', 4, 'TepK', '', 'SP', '', 'TH2', '', '["05A"]', '[""]', 'die', 16, 'E', ''),
(11, '2018-03-07', 5, 'HanB', '', 'VFG', '', 'n02', 'n02', '["05A"]', '[""]', 'die', 16, 'E', ''),
(12, '2018-03-07', 5, 'BreM', '', 'VFG', '', 'n02', 'n02', '["05A"]', '[""]', 'die', 16, 'L', ''),
(13, '2018-03-07', 6, 'HanB', '', 'D', '', 'n02', 'n02', '["05A"]', '[""]', 'die', 16, 'L', ''),
(14, '2018-03-07', 1, 'ReuD', '', 'MU', '', 'nMu', 'nMu', '["05B"]', '[""]', 'die', 16, 'L', ''),
(15, '2018-03-07', 2, 'ReuD', '', 'MU', '', 'nMu', 'nMu', '["05B"]', '[""]', 'die', 16, 'L', ''),
(16, '2018-03-07', 4, 'KwaJ', '', 'M', '', 'n03', 'n03', '["05B"]', '[""]', 'die', 16, 'L', ''),
(17, '2018-03-07', 5, 'BunK', '', 'D', '', 'n03', 'n03', '["05B"]', '[""]', 'die', 16, 'L', ''),
(18, '2018-03-07', 6, 'BunK', '', 'VFG', '', 'n03', 'n03', '["05B"]', '[""]', 'die', 16, 'L', ''),
(19, '2018-03-07', 6, 'KwaJ', '', 'VFG', '', 'n03', 'n03', '["05B"]', '[""]', 'die', 24, 'L', ''),
(20, '2018-03-09', 1, 'LelH', '', 'E', '', 'n04', '', '["05C"]', '[""]', 'die', 16, 'L', ''),
(21, '2018-03-09', 2, 'LelH', '', 'E', '', 'n04', '', '["05C"]', '[""]', 'die', 16, 'L', ''),
(22, '2018-03-09', 3, 'LanA', '', 'SP', '', 'THGY', 'THGY', '["05C"]', '[""]', 'die', 16, 'L', ''),
(23, '2018-03-09', 4, 'LanA', '', 'SP', '', 'THGY', 'THGY', '["05C"]', '[""]', 'die', 16, 'L', ''),
(24, '2018-03-09', 5, 'PouM', '', 'GE', '', 'n04', '', '["05C"]', '[""]', 'die', 16, 'L', ''),
(25, '2018-03-09', 6, 'PouM', '', 'GE', '', 'n04', '', '["05C"]', '[""]', 'die', 16, 'L', ''),
(26, '2018-03-07', 1, 'AusC', '', 'D', '', 'n07', 'n07', '["05D"]', '[""]', 'die', 16, 'L', ''),
(27, '2018-03-07', 2, 'BerJ', '', 'E', '', 'n07', 'n07', '["05D"]', '[""]', 'die', 16, 'L', ''),
(28, '2018-03-07', 3, 'BerJ', '', 'E', '', 'n07', 'n07', '["05D"]', '[""]', 'die', 16, 'L', 'Aufgaben siehe Mail!'),
(29, '2018-03-07', 4, 'BluH', '', 'M', '', 'n07', '', '["05D"]', '[""]', 'die', 16, 'L', ''),
(30, '2018-03-07', 5, 'SchA', '', 'BI', '', 'nBio', 'nBio', '["05D"]', '[""]', 'die', 16, 'L', ''),
(31, '2018-03-07', 6, 'SchA', '', 'BI', '', 'nBio', 'nBio', '["05D"]', '[""]', 'die', 24, 'L', ''),
(32, '2018-03-07', 2, 'MatD', 'LelH', 'RELev', 'V', '138', '138', '["09B"]', '["09B"]', 'die', 0, '', ''),
(33, '2018-03-07', 3, 'MatD', '', 're5', '', 'g25', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'die', 1, 'C', ''),
(34, '2018-03-07', 3, 'ReuD', '', 'mu4', '', 'g07', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'die', 1, 'C', ''),
(35, '2018-03-07', 3, 'GraK', '', 'mu2', '', 'nMu', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'die', 1, 'C', ''),
(36, '2018-03-07', 4, 'GraK', '', 'mu2', '', 'nMu', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'die', 1, 'C', ''),
(37, '2018-03-06', 1, 'BluH', '', 'M', '', 'n07', 'n07', '["05D"]', '[""]', 'die', 16, 'L', ''),
(38, '2018-03-06', 2, 'BluH', '', 'M', '', 'n07', 'n07', '["05D"]', '[""]', 'die', 16, 'L', ''),
(39, '2018-03-06', 9, 'KwaJ', '', 'sp5', '', 'THRS', '', '["QU 1\\/2","QU 3\\/4"]', '["QU 1\\/2","QU 3\\/4"]', 'KFL', 1, 'C', ''),
(40, '2018-03-06', 10, 'KwaJ', '', 'sp5', '', 'THRS', '', '["QU 1\\/2","QU 3\\/4"]', '["QU 1\\/2","QU 3\\/4"]', 'KFL', 1, 'C', ''),
(41, '2018-03-06', 9, 'KwaJ', '', 'sp2', '', 'TH', '', '["QU 1\\/2","QU 3\\/4"]', '["QU 1\\/2","QU 3\\/4"]', 'KFL', 1, 'C', ''),
(42, '2018-03-06', 10, 'KwaJ', '', 'sp2', '', 'TH', '', '["QU 1\\/2","QU 3\\/4"]', '["QU 1\\/2","QU 3\\/4"]', 'KFL', 1, 'C', ''),
(43, '2018-03-07', 2, 'BöhW', '', 'EK', '', '045', '045', '["08E"]', '[""]', 'du', 16, 'L', ''),
(44, '2018-03-07', 3, 'BöhW', '', 'D', '', '155', '155', '["10D"]', '[""]', 'du', 16, 'L', ''),
(45, '2018-03-07', 4, 'BöhW', '', 'D', '', '155', '155', '["10D"]', '[""]', 'du', 16, 'L', ''),
(46, '2018-03-07', 6, 'BöhW', '', 'EK', '', 'g03', 'g03', '["08A"]', '[""]', 'du', 16, 'L', ''),
(47, '2018-03-07', 7, 'BöhW', '', 'DE4', '', 'g18', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'du', 1, 'C', ''),
(48, '2018-03-07', 8, 'BöhW', '', 'DE4', '', 'g18', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'du', 1, 'C', ''),
(49, '2018-03-07', 7, 'BenH', '', 'FD', '', 'n02', 'n02', '["06A","06B","06C","06D","06E"]', '["06A","06B","06C","06D","06E"]', 'KFL', 0, '', ''),
(50, '2018-03-06', 9, 'ReuD', '', 'Orchester', '', '085', '085', '["AG 5 - 13"]', '["AG 5 - 13"]', 'KFL', 0, '', ''),
(51, '2018-03-06', 10, 'ReuD', '', 'Orchester', '', '085', '085', '["AG 5 - 13"]', '["AG 5 - 13"]', 'KFL', 0, '', ''),
(52, '2018-03-07', 3, 'KraI', 'KraI', 'L1', 'L', '046', 'g18', '["07A","07B","07C","07D","07E"]', '["07A","07B","07C","07D","07E"]', '', 0, 'A', ''),
(53, '2018-03-07', 6, 'HorC', '', 'D', '', '154', '', '["10C"]', '["10C"]', '', 1, 'C', ''),
(54, '2018-03-07', 1, 'Fra', '', 'RELev2', '', '149', '', '["09A","09D"]', '["09A","09D"]', 'du', 1, 'C', ''),
(55, '2018-03-07', 2, 'Fra', '', 'RELev2', '', '149', '', '["09A","09D"]', '["09A","09D"]', 'du', 1, 'C', ''),
(56, '2018-03-07', 3, 'Fra', '', 'REL', '', 'g25', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'du', 1, 'C', ''),
(57, '2018-03-07', 4, 'Fra', '', 'REL', '', 'g25', '', '["QU 1\\/2"]', '["QU 1\\/2"]', 'du', 1, 'C', ''),
(58, '2018-03-06', 3, 'KwaJ', '', 'MA2', 'MA2', 'g17', 'g17', '["QU 1\\/2"]', '["QU 1\\/2"]', 'die', 0, '', 'Unterricht findet statt!'),
(59, '2018-03-06', 3, 'SchrM', 'SchrM', 'BI', 'BI', '', '072', '["09E"]', '["09E"]', '', 0, 'R', ''),
(60, '2018-03-06', 4, 'ZorB', 'ZorB', 'BI3', 'BI3', '072', 'g04', '["QU 1\\/2"]', '["QU 1\\/2"]', '', 0, 'R', ''),
(61, '2018-03-06', 3, 'ZorB', 'ZorB', 'BI3', 'BI3', '072', 'g04', '["QU 1\\/2"]', '["QU 1\\/2"]', '', 0, 'R', ''),
(62, '2018-03-06', 7, 'KusS', '', 'KU', '', '049', '', '["10C"]', '["10C"]', '', 1, 'C', ''),
(63, '2018-03-06', 8, 'KusS', '', 'KU', '', '049', '', '["10C"]', '["10C"]', '', 1, 'C', ''),
(64, '2018-03-07', 7, 'KwaJ', 'KwaJ', 'M', 'M', '045', '045', '["08E"]', '["08E"]', '', 0, 'A', 'Unterrichtsverlagerung bitte beachten!'),
(65, '2018-03-07', 8, 'KwaJ', 'KwaJ', 'M', 'M', '045', '045', '["08E"]', '["08E"]', '', 0, 'A', 'Unterrichtsverlagerung bitte beachten!'),
(66, '2018-03-07', 7, 'StoB', '', 'E', '', 'n05', '', '["06E"]', '["06E"]', '', 1, 'C', '');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `untis_users`
--

DROP TABLE IF EXISTS `untis_users`;
CREATE TABLE `untis_users` (
  `id` int(10) NOT NULL,
  `username` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastname` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `type` text COLLATE utf8_unicode_ci,
  `class` text COLLATE utf8_unicode_ci,
  `abbreviation` text COLLATE utf8_unicode_ci,
  `password` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `last_confirmed` date DEFAULT NULL,
  `role` int(2) NOT NULL DEFAULT '1',
  `lastlogin_date` timestamp NULL DEFAULT NULL,
  `lastlogin_ip` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `session_fingerprint` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verifykey` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `untis_main`
--
ALTER TABLE `untis_main`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `untis_substitute_plan`
--
ALTER TABLE `untis_substitute_plan`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `untis_users`
--
ALTER TABLE `untis_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `untis_main`
--
ALTER TABLE `untis_main`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `untis_substitute_plan`
--
ALTER TABLE `untis_substitute_plan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT für Tabelle `untis_users`
--
ALTER TABLE `untis_users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
