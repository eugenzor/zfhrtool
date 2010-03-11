-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 11, 2010 at 07:04 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.10-2ubuntu6.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `zfhrtool`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nickname` varchar(150) NOT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `role` enum('guest','manager','recruit','staff','dismissed','administrator') NOT NULL DEFAULT 'guest',
  `status` enum('verifying','active','blocked') NOT NULL DEFAULT 'verifying',
  `last_login_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_user_email` (`email`),
  UNIQUE KEY `index_user_nickname` (`nickname`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nickname`, `email`, `password`, `role`, `status`, `last_login_at`) VALUES
(1, 'Иванов Иван Иванович', 'ivan@zfhrtool.net', '68e4336d5c5390d05153c9a29a8235c8', 'manager', 'active', '2010-03-10 19:36:53'),
(2, 'Федоров Федор Федорович', 'fedor@zfhrtool.net', '1618fe490d041584a583457fd3f7627f', 'recruit', 'active', '2010-03-10 19:33:48'),
(3, 'Петров Петр Петрович', 'peter@zfhrtool.net', '69b36922923cb75e65d407bd8e8913d3', 'staff', 'active', '2010-03-10 19:34:06');

