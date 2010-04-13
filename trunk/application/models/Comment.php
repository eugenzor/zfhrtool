<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Комментарий ( строка таблицы )
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Comment extends Zend_Db_Table_Row_Abstract {
    /**
     * Constructor.
     *
     * @param  int $applicantId optional
     * @return void
     */
    public function __construct(array $config = array(), $applicantId = null)
    {
        parent :: __construct($config);
        if ( $commentId ) {
            $this->c_id = $commentId;
        }
    }
   /**
    * Get Id
    *
    * @return string
    */
   public function getId() {
      return $this->c_id;
   }

    /**
     * Set Id
     *
     * @param string $id
     */
    public function setId($id) {
       $this->c_id = $id;
    }

   /**
    * Get UserId
    *
    * @return string
    */
   public function getUserId() {
      return $this->c_user_id;
   }
   /**
    * Set UserId
    *
    * @param string $user_id
    */
   public function setUserId($user_id) {
      $this->c_user_id = $user_id;
   }

   /**
    * Get ApplicantId
    *
    * @return string
    */
   public function getApplicantId() {
      return $this->c_applicant_id;
   }
   /**
    * Set ApplicantId
    *
    * @param string $applicant_id
    */
   public function setApplicantId($applicant_id) {
      $this->c_applicant_id = $applicant_id;
   }

   /**
    * Get Date
    *
    * @return string
    */
   public function getDate() {
        $date = new Zend_Date( $this -> c_date);
        return $date->toString('dd.MM.yyyy');
   }

   /**
    * Set Date
    *
    * @param string $date
    */
   public function setDate($date) {
      $this->c_date = $date;
   }


   /**
    * Get Comment
    *
    * @return string
    */
   public function getComment() {
      return $this->c_comment;
   }
   /**
    * Set Comment
    *
    * @param string $comment
    */
   public function setMessage($comment) {
      $this->c_comment = $comment;
   }
}