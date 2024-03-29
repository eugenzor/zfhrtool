<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Вопрос (строка таблицы)
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Question extends Zend_Db_Table_Row_Abstract {
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
            $this->tq_id = $id;
        }
    }
   /**
    * Get Id
    *
    * @return string
    */
   public function getId() {
      return $this->tq_id;
   }

    /**
     * Set Id
     *
     * @param string $id
     */
    public function setId($id) {
       $this->tq_id = $id;
    }

   /**
    * Get Tezt
    *
    * @return string
    */
   public function getText() {
      return $this->tq_text;
   }
   /**
    * Set text
    *
    * @param string $text
    */
   public function setText($text) {
      $this->tq_text = $text;
   }

   /**
    * Get weight
    *
    * @return string
    */
   public function getWeight() {
      return $this->tq_weight;
   }
   /**
    * Set weight
    *
    * @param string $weight
    */
   public function setWeight($weight) {
      $this->tq_weight = $weight;
   }

   /**
     * Get AnswerAmount
     *
     * @return string
     */
    public function getAnswerAmount() {
       return $this->tq_answer_amount;
    }
    /**
     * Set AnswerAmount
     *
     * @param string $answeramount
     */
    public function setAnswerAmount($answerAmount) {
       $this->tq_answer_amount = $answerAmount;
    }

    /**
     * Get sortIndex
     *
     * @return string
     */
    public function getSortIndex() {
       return $this->tq_sort_index;
    }
    /**
     * Set SortIndex
     *
     * @param string $index
     */
    public function setSortIndex($index) {
       $this->tq_sort_index = $index;
    }
    /**
     * Get testId
     *
     * @return string
     */
    public function getTestId() {
       return $this->t_id;
    }
    /**
     * Set TestId
     *
     * @param string $id
     */
    public function setTestId($id) {
       $this->t_id = $id;
    }

    /**
     * Get RightAnswersAmount
     *
     * @return string
     */
    public function getRightAnswersAmount() {
       return $this->tq_right_answers_amount;
    }
    /**
     * Update RightAnswersAmount
     *
     * @return void
     */
    public function updateRightAnswersAmount() {
        if ( !$this->getId() ) {
            $this->save();
        }
        $objAnswers = new Answers();
        $this->tq_right_answers_amount = $objAnswers->countRightAnswers( $this->getId() );
    }
    /**
     * Get questions category id
     *
     * @return string
     */
    public function getCategoryId() {
       return $this->tqc_id;
    }
    /**
     * Set question category id
     *
     * @param string $id
     */
    public function setCategoryId($id) {
       $this->tqc_id = $id;
    }

    /**
     * Get RightAnswersAmount
     *
     * @return string
     */
}