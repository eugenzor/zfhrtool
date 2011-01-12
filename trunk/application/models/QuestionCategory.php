<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Категория тестов (строка таблицы)
 *
 * @package zfhrtool
 * @subpackage Model
 */
class QuestionCategory extends Zend_Db_Table_Row_Abstract {
   /**
    * Get Id
    *
    * @return string
    */
   public function getId() {
      return $this->tqc_id;
   }

   /**
    * Get Name
    *
    * @return string
    */
   public function getName() {
      return $this->tqc_name;
   }
   /**
    * Set name
    *
    * @param string $name
    */
   public function setName($name) {
      $this->tqc_name = $name;
   }
   /**
    * Get Description
    *
    * @return string
    */
   public function getDescription() {
      return $this->tqc_descr;
   }
   /**
    * Set Description
    *
    * @param string $description
    */
   public function setDescription($description) {
      $this->tqc_descr = $description;
   }
   /**
    * Get TestId
    *
    * @return string
    */
   public function getTestId() {
      return $this->t_id;
   }
   /**
    * Set TestId
    *
    * @param string $testId
    */
   public function setTestId($testId) {
      $this->t_id = $testId;
   }
}