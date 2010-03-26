-- phpMyAdmin SQL Dump
-- version 2.11.9.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 19, 2010 at 04:15 AM
-- Server version: 5.0.67
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `zfhrtool`
--

-- --------------------------------------------------------

--
-- Table structure for table `mg_category`
--

CREATE TABLE IF NOT EXISTS `mg_category` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `cat_name` varchar(255) NOT NULL,
  `cat_descr` text,
  `cat_test_amount` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Category list table' AUTO_INCREMENT=14 ;

--
-- Dumping data for table `mg_category`
--

INSERT INTO `mg_category` (`cat_id`, `cat_name`, `cat_descr`, `cat_test_amount`) VALUES
(1, 'Common', 'Коммент1 edited one more time', 0),
(2, 'PHP', 'Коментарий . 2', 0),
(5, 'JavaScript ', 'Инфо ', 0),
(13, 'апапап', 'hghjhj', 0);

-- --------------------------------------------------------

--
-- Table structure for table `mg_test`
--

CREATE TABLE IF NOT EXISTS `mg_test` (
  `t_id` int(10) unsigned NOT NULL auto_increment,
  `t_name` varchar(255) NOT NULL,
  `t_quest_amount` int(11) NOT NULL,
  `t_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `cat_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`t_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `mg_test`
--

INSERT INTO `mg_test` (`t_id`, `t_name`, `t_quest_amount`, `t_date`, `cat_id`) VALUES
(5, 'Продвинутый тест по PHP 5 (ООП)', 8, '2010-03-18 03:51:09', 2),
(9, 'fgdfgfdg', 1, '2010-03-19 02:29:01', 13),
(10, 'test 1', 0, '2010-03-19 03:59:16', 1),
(11, 'еще тест', 1, '2010-03-19 03:59:29', 1);

-- --------------------------------------------------------

--
-- Table structure for table `mg_test_question`
--

CREATE TABLE IF NOT EXISTS `mg_test_question` (
  `tq_id` int(10) unsigned NOT NULL auto_increment,
  `tq_text` text NOT NULL,
  `tq_answer_amount` int(10) unsigned NOT NULL default '0',
  `tq_sort_index` int(10) unsigned NOT NULL default '0',
  `t_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`tq_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Dumping data for table `mg_test_question`
--

INSERT INTO `mg_test_question` (`tq_id`, `tq_text`, `tq_answer_amount`, `tq_sort_index`, `t_id`) VALUES
(9, 'что такое ООП?', 3, 1, 5),
(10, 'что является php фреймворком?\r\nAjax \r\nJQuery\r\nZend Framework', 3, 2, 5),
(14, 'вопрос', 7, 3, 5),
(15, 'new', 3, 6, 5),
(19, 'aaaaa', 0, 4, 5),
(20, 'ssssss', 0, 5, 5),
(21, 'dddddd', 0, 7, 5),
(22, 'fffff', 0, 8, 5),
(23, 'fff', 3, 9, 9),
(24, 'вопрос', 3, 10, 11);

-- --------------------------------------------------------

--
-- Table structure for table `mg_test_question_answer`
--

CREATE TABLE IF NOT EXISTS `mg_test_question_answer` (
  `tqa_id` int(10) unsigned NOT NULL auto_increment,
  `tqa_text` text NOT NULL,
  `tqa_flag` tinyint(1) NOT NULL,
  `tq_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`tqa_id`),
  KEY `tq_id` (`tq_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=70 ;

--
-- Dumping data for table `mg_test_question_answer`
--

INSERT INTO `mg_test_question_answer` (`tqa_id`, `tqa_text`, `tqa_flag`, `tq_id`) VALUES
(40, 'не знаю', 0, 9),
(41, 'не доставайте меня ', 0, 9),
(42, 'Объектно ориентированное программирование', 1, 9),
(43, 'Ajax ', 0, 10),
(44, 'JQuery', 0, 10),
(45, 'Zend Framework', 1, 10),
(54, '1', 0, 14),
(55, '2', 0, 14),
(56, '3', 1, 14),
(57, '4', 0, 14),
(58, '5', 1, 14),
(59, '6', 0, 14),
(60, '7', 0, 14),
(61, 'ww', 1, 15),
(62, 'ee', 1, 15),
(63, 'rrr', 0, 15),
(64, 'gggg', 0, 23),
(65, 'hhhh', 1, 23),
(66, 'jjjj', 0, 23),
(67, '1', 0, 24),
(68, '2', 0, 24),
(69, '3', 0, 24)d