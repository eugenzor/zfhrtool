<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Ответ (строка таблицы)
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Answer extends Zend_Db_Table_Row_Abstract {
    /**
     * Constructor.
     *
     * @param  int $id optional
     * @return void
     */
    public function __construct(array $config = array(), $id = null)
    {
        parent :: __construct($config);
        if ( $id ) {
            $this->tqa_id = $id;
        }
    }
   /**
    * Get Id
    *
    * @return string
    */
   public function getId() {
      return $this->tqa_id;
   }

    /**
     * Set Id
     *
     * @param string $id
     */
    public function setId($id) {
       $this->tqa_id = $id;
    }

   /**
    * Get Tezt
    *
    * @return string
    */
   public function getText() {
      return $this->tqa_text;
   }
   /**
    * Set text
    *
    * @param string $text
    */
   public function setText($text) {
      $this->tqa_text = $text;
   }

    /**
     * Get flag
     *
     * @return string
     */
    public function getFlag() {
       return $this->tqa_flag;
    }
    /**
     * Set Flag
     *
     * @param string $flag
     */
    public function setFlag( $flag ) {
       $this->tqa_flag = $flag ? true : false;
    }
    
    /**
     * Get questionId
     *
     * @return string
     */
    public function getQuestionId() {
       return $this->tq_id;
    }
    /**
     * Set questionId
     *
     * @param int $id
     */
    public function setQuestionId($id) {
       $this->tq_id = $id;
    }
}