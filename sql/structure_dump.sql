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
