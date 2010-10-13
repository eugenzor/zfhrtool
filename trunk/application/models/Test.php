<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Тест ( строка таблицы )
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Test extends Zend_Db_Table_Row_Abstract {
    /**
     * Constructor.
     *
     * @param  int $testId optional
     * @return void
     */
    public function __construct(array $config = array(), $testId = null)
    {
        parent :: __construct($config);
        if ( $testId ) {
            $this->t_id = $testId;
        }
    }
   /**
    * Get Id
    *
    * @return string
    */
   public function getId() {
      return $this->t_id;
   }

    /**
     * Set Id
     *
     * @param string $id
     */
    public function setId($id) {
       $this->t_id = $id;
    }

   /**
    * Get Name
    *
    * @return string
    */
   public function getName() {
      return $this->t_name;
   }
   /**
    * Set name
    *
    * @param string $name
    */
   public function setName($name) {
      $this->t_name = $name;
   }

   /**
    * Get Description
    *
    * @return string
    */
   public function getDate() {
      return $this->t_date;
   }
   /**
    * Get Testamount
    *
    * @return string
    */
   public function getQuestionAmount() {
      return $this->t_quest_amount;
   }
   /**
    * Set Testamount
    *
    * @param string $testamount
    */
   public function setQuestionAmount($questionAmount) {
      $this->t_quest_amount = $questionAmount;
   }

    /**
     * Get CategoryId
     *
     * @return string
     */
    public function getCategoryId() {
       return $this->cat_id;
    }
    /**
     * Set CategoryId
     *
     * @param string $id
     */
    public function setCategoryId($id) {
       $this->cat_id = $id;
    }

    /**
    * Get time
    *
    * @return int
    */
   public function getTime() {
      return $this->time;
   }

    /**
     * Set time
     *
     * @param int $time
     */
    public function setTime($time) {
       $this->time = $time;
    }
}