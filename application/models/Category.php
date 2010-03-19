<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Категория
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Category extends Zend_Db_Table_Row_Abstract {
   /**
    * Get Id
    *
    * @return string
    */
   public function getId() {
      return $this->cat_id;
   }

    /**
     * Set Id
     *
     * @param string $id
     */
    public function setId($id) {
       $this->cat_id = $id;
    }

   /**
    * Get Name
    *
    * @return string
    */
   public function getName() {
      return $this->cat_name;
   }
   /**
    * Set name
    *
    * @param string $name
    */
   public function setName($name) {
      $this->cat_name = $name;
   }
   /**
    * Get Description
    *
    * @return string
    */
   public function getDescription() {
      return $this->cat_descr;
   }
   /**
    * Set Description
    *
    * @param string $description
    */
   public function setDescription($description) {
      $this->cat_descr = $description;
   }
   /**
    * Get Testamount
    *
    * @return string
    */
   public function getTestAmount() {
      return $this->cat_test_amount;
   }
   /**
    * Set Testamount
    *
    * @param string $testamount
    */
   public function setTestAmount($testAmount) {
      $this->cat_test_amount = $testAmount;
   }
}