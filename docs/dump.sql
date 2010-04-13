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
(3, 'Петров Петр Петрович', 'peter@zfhrtool.net', '69b36922923cb75e65d407bd8e8913d3', 'staff', 'active', '2010-03-10 19:34:06'),
(4, 'meestro', 'meestro@ukr.net', '7695596c92c750d6087d3be1a8c25147', 'manager', 'active', '2010-03-30 01:15:10');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `cat_id` int(10) unsigned NOT NULL auto_increment,
  `cat_name` varchar(255) NOT NULL,
  `cat_descr` text,
  `cat_test_amount` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Category list table' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`, `cat_descr`, `cat_test_amount`) VALUES
(1, 'Общие', '', 0),
(2, 'PHP', 'тесты по PHP', 0),
(3, '.NET', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `t_id` int(10) unsigned NOT NULL auto_increment,
  `t_name` varchar(255) NOT NULL,
  `t_quest_amount` int(11) NOT NULL,
  `t_date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `cat_id` int(11) unsigned NOT NULL,
  PRIMARY KEY  (`t_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`t_id`, `t_name`, `t_quest_amount`, `t_date`, `cat_id`) VALUES
(1, 'Психологический тест', 3, '2010-03-30 01:26:49', 1),
(2, 'Общий тест по PHP', 4, '2010-03-30 01:36:19', 2),
(3, 'PHP (ООП)', 3, '2010-03-30 01:41:57', 2);

-- --------------------------------------------------------

--
-- Table structure for table `test_question`
--

CREATE TABLE IF NOT EXISTS `test_question` (
  `tq_id` int(10) unsigned NOT NULL auto_increment,
  `tq_text` text NOT NULL,
  `tq_answer_amount` int(10) unsigned NOT NULL default '0',
  `tq_sort_index` int(10) unsigned NOT NULL default '0',
  `t_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`tq_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `test_question`
--

INSERT INTO `test_question` (`tq_id`, `tq_text`, `tq_answer_amount`, `tq_sort_index`, `t_id`) VALUES
(1, 'Первый психологический вопрос', 3, 1, 1),
(2, 'Второй психологический вопрос', 2, 2, 1),
(3, 'Третий психологический вопрос', 2, 3, 1),
(4, 'Ворос по PHP 1', 2, 1, 2),
(5, 'Вопрос по PHP 2', 3, 2, 2),
(6, 'что такое ООП ?', 3, 1, 3),
(7, 'какое ключевое слово используют для объявления класса?', 5, 2, 3),
(8, 'как называется экземпляр класса', 3, 3, 3),
(9, 'Вопрос по PHP 4', 2, 4, 2),
(10, 'Вопрос по PHP 3', 2, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `test_question_answer`
--

CREATE TABLE IF NOT EXISTS `test_question_answer` (
  `tqa_id` int(10) unsigned NOT NULL auto_increment,
  `tqa_text` text NOT NULL,
  `tqa_flag` tinyint(1) NOT NULL,
  `tq_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`tqa_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

--
-- Dumping data for table `test_question_answer`
--

INSERT INTO `test_question_answer` (`tqa_id`, `tqa_text`, `tqa_flag`, `tq_id`) VALUES
(18, '1', 0, 1),
(19, '2', 1, 1),
(20, '3', 0, 1),
(21, '2 1', 1, 2),
(22, '2 2', 1, 2),
(23, '3 1', 0, 3),
(24, '3 2', 0, 3),
(25, 'ООП', 0, 6),
(26, 'Объектно=ориенторованное программирование', 1, 6),
(27, 'Ого-го!!!', 0, 6),
(33, 'implements', 0, 7),
(34, 'function', 0, 7),
(35, 'discinct', 0, 7),
(36, 'className', 0, 7),
(37, 'class', 1, 7),
(38, 'вид', 0, 8),
(39, 'контроллер', 0, 8),
(40, 'объект', 1, 8),
(47, 'ответ по php 2 1', 0, 5),
(48, 'ответ по php 2 2', 1, 5),
(49, 'ответ по php 2 3', 1, 5),
(54, 'отв 5', 0, 10),
(55, 'отв 6', 1, 10),
(56, 'sds', 0, 9),
(57, 'sdsd', 0, 9);

-- --------------------------------------------------------

--
-- Table structure for table `vacancies`
--

CREATE TABLE IF NOT EXISTS `vacancies` (
  `v_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `v_num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `v_name` varchar(255) NOT NULL,
  `v_duties` text,
  `v_requirements` text,
  PRIMARY KEY (`v_id`),
  UNIQUE KEY `index_vacancy_name` (`v_name`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Vacancies list table' AUTO_INCREMENT=3 ;


--
-- Dumping data for table `vacancies`
--

INSERT INTO `vacancies` (`v_id`, `v_num`, `v_name`, `v_duties`, `v_requirements`) VALUES
(1, 2, 'PHP программист', 'писать php код', '- Уверенные знания php (OOП для php5)\r\n- Уверенные знания mysql (объединения, вложенные запросы, триггеры)\r\n- Уверенные знания javascript\r\n- Хорошие знания xhtml, css, навыки верстки\r\n- Навыки работы в unix-системах\r\n- Аналитическое мышление, способность решать сложные задачи \r\n- Знание английского языка'),
(2, 1, 'Верстальщик', 'Верстать сайт', 'Знание css, html');

-- --------------------------------------------------------

--
-- Table structure for table `applicants`
--

CREATE TABLE IF NOT EXISTS `applicants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL DEFAULT '',
  `patronymic` varchar(25) NOT NULL,
  `birth` datetime NOT NULL,
  `v_id` int(10) unsigned NOT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  `phone` char(10) NOT NULL DEFAULT '',
  `resume` text NOT NULL,
  `status` enum('new','invited','interviewed','rejected','taken','staff','dismissed') NOT NULL DEFAULT 'new',
  `number` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_user_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`id`, `name`, `last_name`, `patronymic`, `birth`, `v_id`, `email`, `phone`, `resume`, `status`, `number`) VALUES
(16, 'Иван', 'Иванов', 'Иванович', '1998-08-24 00:00:00', 1, 'ivanov@ukr.net', '0671234567', 'Николаев Николай Андреевич\r\nтел.: (8000) 000 00 00, e-mail: nikolaynikolaev@nikolaev___.com \r\n\r\nЦель:\r\n\r\n соискание должности менеджера по продаже рекламных площадей.\r\n\r\nОпыт работы:\r\n\r\n2007-2004, ООО &amp;amp;amp;amp;laquo;ХХХ&amp;amp;amp;amp;raquo; редакция журналов &amp;amp;amp;amp;laquo;ЗЗЗ&amp;amp;amp;amp;raquo;, &amp;amp;amp;amp;laquo;МММ&amp;amp;amp;amp;raquo; и газеты &amp;amp;amp;amp;laquo;ККК&amp;amp;amp;amp;raquo;, более 40 человек, г. Киев.\r\nМенеджер по продаже рекламных площадей:\r\n- внедрил ежемесячную отчётность  для анализа деятельности отдела продаж рекламных площадей;\r\n- увеличил объем продаж рекламной площади журнала &amp;amp;amp;amp;laquo;ЗЗЗ&amp;amp;amp;amp;raquo; на 17% в год. \r\n                                                                                                                \r\n2001-2004, ООО &amp;amp;amp;amp;laquo;УУУ&amp;amp;amp;amp;raquo; холдинговая компания, более 3000 человек, отдел координации и организации радиостанции &amp;amp;amp;amp;laquo;ЧЧЧ&amp;amp;amp;amp;raquo; на волне 432 FM, отдел состоял из 12 человек, г. Киев. \r\nМенеджер по продаже рекламных площадей:\r\n- неоднократно был отмечен как лучший менеджер месяца, перевыполняющий план по продажам времени эфира радио &amp;amp;amp;amp;laquo;ЧЧЧ&amp;amp;amp;amp;raquo; и сбору дебиторской задолженности;\r\n- ежегодно увеличивал объем продаж эфирного времени на 10%.\r\n\r\nОбразование: \r\n\r\n1996-2001, Киевский политехнический институт, г. Киев;\r\nФакультет экономики, менеджер, диплом с отличием.\r\n2003, Бизнес центр &amp;amp;amp;amp;laquo;Лидер&amp;amp;amp;amp;raquo;, курс &amp;amp;amp;amp;laquo;Маркетинг и реклама&amp;amp;amp;amp;raquo;. \r\n\r\nДополнительные сведения: \r\n\r\nОпытный пользователь ПК, MS Office, Internet.\r\nСамостоятелен, коммуникабелен, ответственен, ориентирован на результат.\r\n\r\nДанный пример резюме представляет достаточно качественное резюме, которое подойдет большинству работодателей. \r\n\r\nБолее подробную информацию о структуре резюме и 5 реальных примеров молодежных резюме смотрите в справочнике карьеры.\r\n\r\nЕсли у вас еще нет резюме - вы можете создать его на сайте topwork.com.ua используя удобный конструктор резюме. Вам нужно будет заполнить анкету и вы получите готовое качественное резюме, которое сможете рассылать работодателям.', 'invited', 0),
(17, 'Петр', 'Петров', 'Петрович', '1987-02-12 00:00:00', 2, 'petr@ukr.net', '0971234567', 'Фамилия Имя Отчество\r\n\r\nКиев, ул. Соискателей 12, кв. 45 \r\nтел. 777-7777 \r\ne-mail: seeker@jobs.ua\r\n\r\nЦель:получение должности html-верстальщик\r\n\r\nОпыт работы:\r\n2008- наст.вр. &ndash; &laquo;&hellip;&raquo;, html-верстальщик, контент-мастер\r\nВыполняемые обязанности: \r\nHTML верстка\r\nне сложный дизайн\r\nнарезка дизайна\r\nподдержка и обновление сайта\r\nсканирование и обработка изображений и текстов\r\nоптимизация и продвижение сайтов\r\n\r\n2007 &ndash; 2008 &ndash; &laquo;&hellip;&raquo;, html-верстальщик\r\nВыполняемые обязанности: \r\nверстка HTML/CSS/JavaScript на основе макетов, созданных дизайнером\r\nприведение существующих интерфейсов web-приложений в соответствие с требованиями W3C Accessibility (совместно с разработчиками системы)\r\nнастройка, поддержка и обновление сайтов на CMS (совместно с разработчиками системы)\r\n\r\n2006 - 2007 &ndash; &laquo;&hellip;&raquo;, html-верстальщик\r\nВыполняемые обязанности: \r\nHTML-верстка PSD-макетов\r\nразработка не сложных программных решений (PHP + MySQL + JavaScript + AJAX)\r\nобновление существующих решений&sbquo; исправление ошибок и т.п.\r\nнаполнение контентом\r\nОбразование:\r\n2001-2006 - Харьковский национальный университет радиоэлектроники\r\nФакультет: Компьютерные науки\r\nСпециальность: компьютеризированные технологии и системы издательско-полиграфических предприятий\r\n\r\nЗнания и навыки:\r\nотличнное знание ПК и Windows на уровне опытного пользователя\r\nсвободно работаю с пакетом MS Office\r\nдизайнерские программы: Пакет Adobe (Photoshop, ImageReader, Illustrator), Corel Draw \r\nбазовые знания по Corel Bryce, 3DMax\r\nHTML, CSS, SSI\r\nобработка изображений и составление коллажей в Adobe Photoshop\r\nверстка шаблонов HTML:Template\r\nполное понимание работы интерактивных сайтов и методов их построения\r\nнарезка дизайн-макетов сайтов\r\nначальный дизайн сайтов\r\nопыт кроссброузерной верстки сложных дизайнов\r\nиностранные языки: английский (начальный уровень)\r\nДополнительная информация:\r\nкоммуникабельность, усидчивость, обязательность, обучаемость, пунктуальность&sbquo; ответственность&sbquo; внимательность&sbquo; исполнительность', 'new', 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_user_id` int(10) unsigned NOT NULL,
  `c_comment` text,
  `c_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `c_applicant_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`c_id`),
  KEY `comments_applicant` (`c_applicant_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`c_id`, `c_user_id`, `c_comment`, `c_date`, `c_applicant_id`) VALUES
(18, 5, 'Applicant added to base', '2010-04-13 18:23:24', 16),
(19, 5, 'Applicant status changed to "invited"<br>хороший опыт работы', '2010-04-13 19:43:18', 16),
(20, 5, 'Applicant added to base', '2010-04-13 19:45:11', 17),
(21, 5, 'портфолио хорошее', '2010-04-13 19:45:45', 17);