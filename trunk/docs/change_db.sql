ALTER TABLE `test_question` ADD `tq_weight` TINYINT UNSIGNED NOT NULL DEFAULT '1';
ALTER TABLE `test_question` ADD `tq_right_answers_amount` INT UNSIGNED NULL AFTER `tq_answer_amount`;
ALTER TABLE `test_question` ADD `tqc_id` INT UNSIGNED NULL;
ALTER TABLE `test_question` ADD INDEX ( `tqc_id` );

UPDATE `test_question` SET `tq_right_answers_amount` = (
    SELECT COUNT(*) 
    FROM `test_question_answer` 
    WHERE `tq_id` = `test_question`.`tq_id` 
    AND `tqa_flag` = 1
);

ALTER TABLE `test_question` MODIFY `tq_right_answers_amount` INT UNSIGNED NOT NULL;

ALTER TABLE `applicant_tests` ADD `score` DECIMAL(6,2) NULL;
ALTER TABLE `applicant_tests` ADD INDEX ( `score` ) ;


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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
