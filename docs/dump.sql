-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 12, 2011 at 11:17 AM
-- Server version: 5.1.40
-- PHP Version: 5.2.12

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
-- Table structure for table `applicants`
--

DROP TABLE IF EXISTS `applicants`;
CREATE TABLE IF NOT EXISTS `applicants` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL DEFAULT '',
  `patronymic` varchar(25) NOT NULL,
  `birth` datetime NOT NULL,
  `vacancy_id` int(10) unsigned NOT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  `phone` char(10) NOT NULL DEFAULT '',
  `resume` text NOT NULL,
  `status` enum('new','invited','interviewed','rejected','taken','staff','dismissed') NOT NULL DEFAULT 'new',
  `number` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_user_email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `applicants`
--

INSERT INTO `applicants` (`id`, `name`, `last_name`, `patronymic`, `birth`, `vacancy_id`, `email`, `phone`, `resume`, `status`, `number`) VALUES
(16, 'Иван', 'Иванов', 'Иванович', '1998-08-24 00:00:00', 1, 'ivanov@ukr.net', '0671234567', 'Николаев Николай Андреевич\r\nтел.: (8000) 000 00 00, e-mail: nikolaynikolaev@nikolaev___.com \r\n\r\nЦель:\r\n\r\n соискание должности менеджера по продаже рекламных площадей.\r\n\r\nОпыт работы:\r\n\r\n2007-2004, ООО &amp;amp;amp;amp;laquo;ХХХ&amp;amp;amp;amp;raquo; редакция журналов &amp;amp;amp;amp;laquo;ЗЗЗ&amp;amp;amp;amp;raquo;, &amp;amp;amp;amp;laquo;МММ&amp;amp;amp;amp;raquo; и газеты &amp;amp;amp;amp;laquo;ККК&amp;amp;amp;amp;raquo;, более 40 человек, г. Киев.\r\nМенеджер по продаже рекламных площадей:\r\n- внедрил ежемесячную отчётность  для анализа деятельности отдела продаж рекламных площадей;\r\n- увеличил объем продаж рекламной площади журнала &amp;amp;amp;amp;laquo;ЗЗЗ&amp;amp;amp;amp;raquo; на 17% в год. \r\n                                                                                                                \r\n2001-2004, ООО &amp;amp;amp;amp;laquo;УУУ&amp;amp;amp;amp;raquo; холдинговая компания, более 3000 человек, отдел координации и организации радиостанции &amp;amp;amp;amp;laquo;ЧЧЧ&amp;amp;amp;amp;raquo; на волне 432 FM, отдел состоял из 12 человек, г. Киев. \r\nМенеджер по продаже рекламных площадей:\r\n- неоднократно был отмечен как лучший менеджер месяца, перевыполняющий план по продажам времени эфира радио &amp;amp;amp;amp;laquo;ЧЧЧ&amp;amp;amp;amp;raquo; и сбору дебиторской задолженности;\r\n- ежегодно увеличивал объем продаж эфирного времени на 10%.\r\n\r\nОбразование: \r\n\r\n1996-2001, Киевский политехнический институт, г. Киев;\r\nФакультет экономики, менеджер, диплом с отличием.\r\n2003, Бизнес центр &amp;amp;amp;amp;laquo;Лидер&amp;amp;amp;amp;raquo;, курс &amp;amp;amp;amp;laquo;Маркетинг и реклама&amp;amp;amp;amp;raquo;. \r\n\r\nДополнительные сведения: \r\n\r\nОпытный пользователь ПК, MS Office, Internet.\r\nСамостоятелен, коммуникабелен, ответственен, ориентирован на результат.\r\n\r\nДанный пример резюме представляет достаточно качественное резюме, которое подойдет большинству работодателей. \r\n\r\nБолее подробную информацию о структуре резюме и 5 реальных примеров молодежных резюме смотрите в справочнике карьеры.\r\n\r\nЕсли у вас еще нет резюме - вы можете создать его на сайте topwork.com.ua используя удобный конструктор резюме. Вам нужно будет заполнить анкету и вы получите готовое качественное резюме, которое сможете рассылать работодателям.', 'invited', 0),
(17, 'Петр', 'Петров', 'Петрович', '1987-02-12 00:00:00', 2, 'petr@ukr.net', '0971234567', 'Фамилия Имя Отчество\r\n\r\nКиев, ул. Соискателей 12, кв. 45 \r\nтел. 777-7777 \r\ne-mail: seeker@jobs.ua\r\n\r\nЦель:получение должности html-верстальщик\r\n\r\nОпыт работы:\r\n2008- наст.вр. &ndash; &laquo;&hellip;&raquo;, html-верстальщик, контент-мастер\r\nВыполняемые обязанности: \r\nHTML верстка\r\nне сложный дизайн\r\nнарезка дизайна\r\nподдержка и обновление сайта\r\nсканирование и обработка изображений и текстов\r\nоптимизация и продвижение сайтов\r\n\r\n2007 &ndash; 2008 &ndash; &laquo;&hellip;&raquo;, html-верстальщик\r\nВыполняемые обязанности: \r\nверстка HTML/CSS/JavaScript на основе макетов, созданных дизайнером\r\nприведение существующих интерфейсов web-приложений в соответствие с требованиями W3C Accessibility (совместно с разработчиками системы)\r\nнастройка, поддержка и обновление сайтов на CMS (совместно с разработчиками системы)\r\n\r\n2006 - 2007 &ndash; &laquo;&hellip;&raquo;, html-верстальщик\r\nВыполняемые обязанности: \r\nHTML-верстка PSD-макетов\r\nразработка не сложных программных решений (PHP + MySQL + JavaScript + AJAX)\r\nобновление существующих решений&sbquo; исправление ошибок и т.п.\r\nнаполнение контентом\r\nОбразование:\r\n2001-2006 - Харьковский национальный университет радиоэлектроники\r\nФакультет: Компьютерные науки\r\nСпециальность: компьютеризированные технологии и системы издательско-полиграфических предприятий\r\n\r\nЗнания и навыки:\r\nотличнное знание ПК и Windows на уровне опытного пользователя\r\nсвободно работаю с пакетом MS Office\r\nдизайнерские программы: Пакет Adobe (Photoshop, ImageReader, Illustrator), Corel Draw \r\nбазовые знания по Corel Bryce, 3DMax\r\nHTML, CSS, SSI\r\nобработка изображений и составление коллажей в Adobe Photoshop\r\nверстка шаблонов HTML:Template\r\nполное понимание работы интерактивных сайтов и методов их построения\r\nнарезка дизайн-макетов сайтов\r\nначальный дизайн сайтов\r\nопыт кроссброузерной верстки сложных дизайнов\r\nиностранные языки: английский (начальный уровень)\r\nДополнительная информация:\r\nкоммуникабельность, усидчивость, обязательность, обучаемость, пунктуальность&sbquo; ответственность&sbquo; внимательность&sbquo; исполнительность', 'new', 0),
(18, 'Игорь', 'Остапюк', 'Викторович', '1988-08-24 00:00:00', 1, 'ihoros@bigmir.net', '8097146517', 'Остапюк Ігор Вікторович\r\ne-mail igor@nemae.info\r\nсайт: http://igor.nemae.info\r\n\r\nМЕТА:\r\nОтримати постійну роботу у сфері розробки веб-проектів на php/javascript. Оскільки я ще вчитимусь рік, то на початку бажаний вільний графік або віддалена робота.\r\n\r\nОСВІТА:\r\n2004 - 2010 рр. - Інститут прикладного системного аналізу, КПІ\r\nСпеціальність &laquo;Системний аналіз і управління&raquo;. \r\nОтримав диплом бакалавра з відзнакою.\r\nНавчаюсь у магістратурі, пишу дипломний проект на тему &laquo;Автоматизація тестування веб-додатків, основаних на скриптових мовах програмування&raquo;.\r\nМаю хорошу базову підготовку з математики.\r\n\r\nДОСВІД РОБОТИ:\r\nПрацював у невеликому колективі компанії ArtAlex (без трудової книжки) та фрілансером. Досвід розробки на php та JavaScript &ndash; близько 5 років. У .NET &ndash; близько 3 років. В команді з двох чоловік створював багатофункціональні движки інтернет-магазинів. Останні найбільш цікаві роботи, посилання на них та додаткову інформацію про мене можна знайти тут: http://igor.nemae.info \r\n\r\n\r\nУМІННЯ ТА НАВИКИ:\r\nМови програмування:\r\n&bull;	php\r\n&bull;	JavaScript\r\n&bull;	C#\r\n&bull;	SQL\r\n\r\nПрацював з існуючими движками:\r\n&bull;	Joomla\r\n&bull;	Coppermine (досить непогано розбираюсь у коді, писав плагіни, змінював теми)\r\n&bull;	PhpBB\r\n&bull;	WordPress\r\n&bull;	Gallery\r\n\r\nСУБД:\r\n&bull;	MySQL\r\n&bull;	MS SQL\r\n\r\nОпераційні системи:\r\n&bull;	Windows\r\n&bull;	Linux *buntu (впевнений користувач з базовими знаннями командного рядку, можу &laquo;підняти&raquo; найнеобхідніші сервери, Апач знаю не досить глибоко)\r\n\r\nМови:\r\n&bull;	українська\r\n&bull;	російська\r\n&bull;	англійська (вільно читаю документацію та англомовні форуми про комп`ютери)\r\n\r\nМаю досвід роботи з технологіями:\r\n&bull;	Smarty\r\n&bull;	AJAX\r\n&bull;	JQuery\r\n&bull;	MooTools\r\n&bull;	SVN\r\n&bull;	WPF\r\n&bull;	Silverlight\r\n\r\nОСОБИСТІ ЯКОСТІ:\r\nВідповідальний, вихований, люблю знаходити найпростіші розв&rsquo;язки складних проблем, писати простий для розуміння код, люблю вивчати нові технології, розв&rsquo;язувати логічні задачі. Досить швидко розбираюсь  чужому коді.', 'invited', 0);

-- --------------------------------------------------------

--
-- Table structure for table `applicant_answers`
--

DROP TABLE IF EXISTS `applicant_answers`;
CREATE TABLE IF NOT EXISTS `applicant_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `applicant_tests_id` smallint(6) NOT NULL,
  `answer_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `applicant_tests_id` (`applicant_tests_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- Dumping data for table `applicant_answers`
--

INSERT INTO `applicant_answers` (`id`, `applicant_tests_id`, `answer_id`) VALUES
(1, 7, 60),
(2, 7, 65),
(3, 7, 63),
(4, 7, 61),
(5, 7, 65),
(6, 7, 63),
(7, 7, 61),
(8, 7, 66),
(9, 7, 63),
(10, 9, 60),
(11, 9, 61),
(12, 9, 62),
(13, 9, 66),
(14, 9, 63),
(15, 10, 65),
(16, 10, 66),
(17, 10, 63),
(18, 11, 60),
(19, 11, 65),
(20, 11, 64),
(21, 12, 61),
(22, 12, 66),
(23, 12, 63),
(24, 13, 60),
(25, 13, 62),
(26, 13, 63),
(27, 15, 61),
(28, 15, 66),
(29, 15, 64),
(30, 15, 61),
(31, 15, 66),
(32, 15, 64),
(33, 16, 61),
(34, 16, 66),
(35, 16, 64),
(36, 17, 61),
(37, 17, 66),
(38, 17, 64),
(39, 18, 61),
(40, 18, 66),
(41, 18, 63),
(42, 18, 64),
(43, 19, 60),
(44, 19, 61),
(45, 19, 62),
(46, 19, 66),
(47, 19, 63),
(48, 20, 61),
(49, 20, 66),
(50, 20, 63),
(51, 24, 60),
(52, 24, 65),
(53, 24, 63),
(54, 26, 60),
(55, 26, 61),
(56, 26, 66),
(57, 27, 60),
(58, 27, 61),
(59, 27, 63),
(60, 29, 61),
(61, 30, 68),
(62, 30, 47),
(63, 30, 54),
(64, 31, 61),
(65, 31, 66);

-- --------------------------------------------------------

--
-- Table structure for table `applicant_tests`
--

DROP TABLE IF EXISTS `applicant_tests`;
CREATE TABLE IF NOT EXISTS `applicant_tests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `applicant_id` smallint(6) NOT NULL,
  `test_id` int(11) NOT NULL,
  `percent` tinyint(4) DEFAULT NULL,
  `link` varchar(32) DEFAULT NULL,
  `score` decimal(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `link` (`link`),
  KEY `atd` (`applicant_id`,`test_id`,`date`),
  KEY `score` (`score`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `applicant_tests`
--

INSERT INTO `applicant_tests` (`id`, `date`, `applicant_id`, `test_id`, `percent`, `link`, `score`) VALUES
(7, '2010-07-20 16:14:27', 16, 1, 0, 'efa3d7c5039b5a4c6d10acc972d60a1d', NULL),
(10, '2010-07-20 16:41:03', 16, 1, 33, '6523ae468b50381ab345b4902bbb3bd0', NULL),
(11, '2010-07-20 16:41:39', 16, 1, 0, 'de1fd73f11864f07370185cc91c6d68c', NULL),
(12, '2010-07-20 17:44:14', 16, 1, 0, '9e56145795f28ab49eece5e79136a3af', NULL),
(13, '2010-07-20 16:42:13', 16, 1, 33, 'f7aa9df2b2a627fc24c3d8f22bda9deb', NULL),
(14, '2010-07-20 16:43:08', 16, 1, NULL, '24ba7a1d9e5c707b0cbdff2b0189f229', NULL),
(19, '2010-07-21 10:19:43', 16, 1, 67, '9a1df9e7773d7f9990c092a9ea1500ba', NULL),
(20, '2010-07-21 11:10:14', 16, 1, 100, '8ef6373cad9e2fe506f4c628df6ee588', NULL),
(25, '2010-07-26 17:59:08', 18, 3, NULL, 'b63335f3ae5d9baf033fc0c202e1de04', NULL),
(27, '2010-07-22 07:54:32', 18, 1, 33, 'da0b70a92d0f87a053d5b191a5db2b50', NULL),
(28, '2010-07-22 12:53:28', 16, 1, 0, 'c1470e681591569c57926a399df3f814', NULL),
(29, '2010-07-22 12:22:21', 17, 1, 33, 'a55a53f3137bff5437dad4b48e747fdf', NULL),
(31, '2010-07-22 12:53:46', 17, 1, 33, '016960a3b0f7e1bd75ce8fe4b7057e89', 2.00),
(32, NULL, 17, 2, NULL, '92c2bc7eb520710774a9d2963c0899f7', NULL),
(33, NULL, 18, 3, NULL, '2551dad1661d671a7eed3055f487526a', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `cat_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(255) NOT NULL,
  `cat_descr` text,
  `cat_test_amount` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cat_id`)
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
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `comment` text,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `applicant_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_applicant` (`applicant_id`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `comment`, `date`, `applicant_id`) VALUES
(18, 5, 'Applicant added to base', '2010-04-13 18:23:24', 16),
(19, 5, 'Applicant status changed to "invited"<br>хороший опыт работы', '2010-04-13 19:43:18', 16),
(20, 5, 'Applicant added to base', '2010-04-13 19:45:11', 17),
(21, 5, 'портфолио хорошее', '2010-04-13 19:45:45', 17),
(22, 5, 'Applicant added to base', '2010-04-15 12:35:12', 18),
(23, 5, 'Applicant status changed from "Вновь добавленный соискатель" to "Приглашен на собеседование"<br>пусть попробует', '2010-04-15 12:44:42', 18),
(25, 5, 'Applicant status changed from "Приглашен на собеседование" to "Штатный сотрудник"<br>отличный работник', '2010-04-15 12:51:04', 16);

-- --------------------------------------------------------

--
-- Table structure for table `test`
--

DROP TABLE IF EXISTS `test`;
CREATE TABLE IF NOT EXISTS `test` (
  `t_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `t_name` varchar(255) NOT NULL,
  `t_quest_amount` int(11) NOT NULL,
  `t_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cat_id` int(11) unsigned NOT NULL,
  `time` tinyint(4) NOT NULL,
  PRIMARY KEY (`t_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`t_id`, `t_name`, `t_quest_amount`, `t_date`, `cat_id`, `time`) VALUES
(1, 'Психологический тест', 3, '2010-03-30 01:26:49', 1, 12),
(2, 'Общий тест по PHP', 4, '2010-03-30 01:36:19', 2, 10),
(3, 'PHP (ООП)', 3, '2010-03-30 01:41:57', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `test_question`
--

DROP TABLE IF EXISTS `test_question`;
CREATE TABLE IF NOT EXISTS `test_question` (
  `tq_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tq_text` text NOT NULL,
  `tq_answer_amount` int(10) unsigned NOT NULL DEFAULT '0',
  `tq_right_answers_amount` int(10) unsigned NOT NULL,
  `tq_sort_index` int(10) unsigned NOT NULL DEFAULT '0',
  `t_id` int(10) unsigned NOT NULL,
  `tq_weight` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `tqc_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`tq_id`),
  KEY `t_id` (`t_id`),
  KEY `tqc_id` (`tqc_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `test_question`
--

INSERT INTO `test_question` (`tq_id`, `tq_text`, `tq_answer_amount`, `tq_right_answers_amount`, `tq_sort_index`, `t_id`, `tq_weight`, `tqc_id`) VALUES
(1, 'Первый психологический вопрос', 3, 1, 1, 1, 3, 5),
(2, 'Второй психологический вопрос', 2, 1, 2, 1, 2, 6),
(3, 'Третий психологический вопрос', 2, 1, 3, 1, 1, 7),
(4, 'Ворос по PHP 1', 2, 1, 1, 2, 2, 1),
(5, 'Вопрос по PHP 2', 3, 2, 2, 2, 1, 2),
(6, 'что такое ООП ?', 3, 1, 1, 3, 1, NULL),
(7, 'какое ключевое слово используют для объявления класса?', 5, 1, 2, 3, 1, NULL),
(8, 'как называется экземпляр класса', 3, 1, 3, 3, 1, NULL),
(9, 'Вопрос по PHP 4', 2, 1, 4, 2, 1, 4),
(10, 'Вопрос по PHP 3', 2, 1, 3, 2, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `test_question_answer`
--

DROP TABLE IF EXISTS `test_question_answer`;
CREATE TABLE IF NOT EXISTS `test_question_answer` (
  `tqa_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tqa_text` text NOT NULL,
  `tqa_flag` tinyint(1) NOT NULL,
  `tq_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tqa_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

--
-- Dumping data for table `test_question_answer`
--

INSERT INTO `test_question_answer` (`tqa_id`, `tqa_text`, `tqa_flag`, `tq_id`) VALUES
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
(63, '3 1', 1, 3),
(64, '3 2', 0, 3),
(65, '2 1', 0, 2),
(66, '2 2', 1, 2),
(67, '1', 0, 4),
(68, '2', 1, 4),
(69, 'sds', 1, 9),
(70, 'sdsd', 0, 9),
(71, '1', 0, 1),
(72, '2', 1, 1),
(73, '3', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `test_question_category`
--

DROP TABLE IF EXISTS `test_question_category`;
CREATE TABLE IF NOT EXISTS `test_question_category` (
  `tqc_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tqc_name` varchar(255) NOT NULL,
  `tqc_descr` text,
  `t_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tqc_id`),
  KEY `t_id` (`t_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `test_question_category`
--

INSERT INTO `test_question_category` (`tqc_id`, `tqc_name`, `tqc_descr`, `t_id`) VALUES
(1, 'категория 1', 'это категория 1', 2),
(2, 'категория 2', 'это категория 2', 2),
(4, 'категория 3', 'это категория 3', 2),
(5, 'категория 1', 'это психологическая категория 1', 1),
(6, 'категория 2', 'это психологическая категория 2', 1),
(7, 'категория 3', 'это психологическая категория 3', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nickname`, `email`, `password`, `role`, `status`, `last_login_at`) VALUES
(1, 'Гость', 'ivan@zfhrtool.net', '68e4336d5c5390d05153c9a29a8235c8', 'guest', 'active', '2010-03-10 19:36:53'),
(2, 'Федоров Федор Федорович', 'fedor@zfhrtool.net', '1618fe490d041584a583457fd3f7627f', 'recruit', 'active', '2010-03-10 19:33:48'),
(3, 'Петров Петр Петрович', 'peter@zfhrtool.net', '69b36922923cb75e65d407bd8e8913d3', 'staff', 'active', '2010-03-10 19:34:06'),
(4, 'meestro', 'meestro@ukr.net', '7695596c92c750d6087d3be1a8c25147', 'manager', 'active', '2010-03-30 01:15:10'),
(5, 'ihor', 'ihoros@bigmir.net', 'e7c58d1fd7fd5bf32311e6eb99ab50dd', 'administrator', 'active', '2010-04-15 12:28:12'),
(6, 'misha', 'l-mi@narod.ru', 'cf3c26e8a6b49fa287e966b9737af876', 'administrator', 'active', '2010-07-26 17:01:01'),
(7, 'vlad', 'noskov.vlad@gmail.com', 'c04127b78802ba45eb9ef36b21945f61', 'administrator', 'active', '2011-01-12 11:08:49');

-- --------------------------------------------------------

--
-- Table structure for table `vacancies`
--

DROP TABLE IF EXISTS `vacancies`;
CREATE TABLE IF NOT EXISTS `vacancies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `num` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `duties` text,
  `requirements` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `index_vacancy_name` (`name`) USING BTREE
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Vacancies list table' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `vacancies`
--

INSERT INTO `vacancies` (`id`, `num`, `name`, `duties`, `requirements`) VALUES
(1, 2, 'PHP программист', 'писать php код', '- Уверенные знания php (OOП для php5)\r\n- Уверенные знания mysql (объединения, вложенные запросы, триггеры)\r\n- Уверенные знания javascript\r\n- Хорошие знания xhtml, css, навыки верстки\r\n- Навыки работы в unix-системах\r\n- Аналитическое мышление, способность решать сложные задачи \r\n- Знание английского языка'),
(2, 1, 'Верстальщик', 'Верстать сайт', 'Знание css, html');

-- --------------------------------------------------------

--
-- Table structure for table `vacancies_test`
--

DROP TABLE IF EXISTS `vacancies_test`;
CREATE TABLE IF NOT EXISTS `vacancies_test` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `vacancy_id` smallint(6) NOT NULL,
  `test_id` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vacancie_id` (`vacancy_id`,`test_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `vacancies_test`
--

INSERT INTO `vacancies_test` (`id`, `vacancy_id`, `test_id`) VALUES
(33, 1, 1),
(34, 1, 2),
(35, 1, 3),
(31, 2, 1),
(32, 2, 2);
