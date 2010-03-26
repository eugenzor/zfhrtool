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
-- Table structure for table `mg_category`
--

TRUNCATE TABLE `mg_category`;

--
-- Dumping data for table `mg_category`
--

INSERT INTO `mg_category` (`cat_id`, `cat_name`, `cat_descr`, `cat_test_amount`) VALUES
(1, 'Common', 'Коммент1 edited one more time', 0),
(2, 'PHP', 'Коментарий . 2', 0),
(5, 'JavaScript ', 'Инфо ', 0),
(13, 'апапап', 'hghjhj', 0);

-- --------------------------------------------------------

TRUNCATE TABLE `mg_test`;

--
-- Dumping data for table `mg_test`
--

INSERT INTO `mg_test` (`t_id`, `t_name`, `t_quest_amount`, `t_date`, `cat_id`) VALUES
(5, 'Продвинутый тест по PHP 5 (ООП)', 8, '2010-03-18 03:51:09', 2),
(9, 'fgdfgfdg', 1, '2010-03-19 02:29:01', 13),
(10, 'test 1', 0, '2010-03-19 03:59:16', 1),
(11, 'еще тест', 1, '2010-03-19 03:59:29', 1);

-- --------------------------------------------------------

TRUNCATE TABLE `mg_test_question_answer`;

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
(69, '3', 0, 24);

-- --------------------------------------------------------

TRUNCATE TABLE `mg_test_question`;

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