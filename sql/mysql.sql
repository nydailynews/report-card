-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 03, 2014 at 01:07 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.2-1ubuntu4.22

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Table structure for table `report_cards`
--

CREATE TABLE IF NOT EXISTS `report_card` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(100) COLLATE utf8_bin NOT NULL,
  `group_slug` varchar(100) COLLATE utf8_bin NOT NULL,
  `title` varchar(250) COLLATE utf8_bin NOT NULL,
  `description` text COLLATE utf8_bin NOT NULL,
  `grade_mean` float NOT NULL,
  `grade_median` float NOT NULL,
  `grades` int(11) NOT NULL,
  `date_created` DATE NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Table structure for table `report_grades`
--

CREATE TABLE IF NOT EXISTS `report_card_grade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `card_id` int(11) NOT NULL,
  `grade` tinyint(4) NOT NULL,
  `ip` varchar(30) COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `id` (`id`),
  KEY `card_id` (`card_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
