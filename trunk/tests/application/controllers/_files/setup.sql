TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nickname`, `email`, `password`, `role`, `status`, `last_login_at`) VALUES
(1, 'Иванов Иван Иванович', 'ivan@zfhrtool.net', '68e4336d5c5390d05153c9a29a8235c8', 'manager', 'verifying', '2010-03-10 19:36:53'),
(2, 'Федоров Федор Федорович', 'fedor@zfhrtool.net', '1618fe490d041584a583457fd3f7627f', 'recruit', 'active', '2010-03-10 19:33:48'),
(3, 'Петров Петр Петрович', 'peter@zfhrtool.net', '69b36922923cb75e65d407bd8e8913d3', 'staff', 'active', '2010-03-10 19:34:06'),
(5, 'meestro', 'meestro@ukr.net', '7695596c92c750d6087d3be1a8c25147', 'manager', 'active', '2010-03-25 12:51:13');

--
-- Table structure for table `category`
--

TRUNCATE TABLE `category`;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`cat_id`, `cat_name`, `cat_descr`, `cat_test_amount`) VALUES
(1, 'Общие', '', 0),
(2, 'PHP', 'тесты по PHP', 0),
(3, '.NET', '', 0);

-- --------------------------------------------------------

TRUNCATE TABLE `test`;

--
-- Dumping data for table `test`
--

INSERT INTO `test` (`t_id`, `t_name`, `t_quest_amount`, `t_date`, `cat_id`) VALUES
(1, 'Психологический тест', 3, '2010-03-30 01:26:49', 1),
(2, 'Общий тест по PHP', 4, '2010-03-30 01:36:19', 2),
(3, 'PHP (ООП)', 3, '2010-03-30 01:41:57', 2);

-- --------------------------------------------------------

TRUNCATE TABLE `test_question_answer`;

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

TRUNCATE TABLE `test_question`;

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