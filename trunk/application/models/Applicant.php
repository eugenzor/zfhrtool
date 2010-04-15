<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Соискатель ( строка таблицы )
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Applicant extends Zend_Db_Table_Row_Abstract {
    /**
     * Constructor.
     *
     * @param  int $applicantId optional
     * @return void
     */
    public function __construct(array $config = array(), $applicantId = null)
    {
        parent :: __construct($config);
        if ( $applicantId ) {
            $this->id = $applicantId;
        }
    }
   /**
    * Get Id
    *
    * @return string
    */
   public function getId() {
      return $this->id;
   }

    /**
     * Set Id
     *
     * @param string $id
     */
    public function setId($id) {
       $this->id = $id;
    }

   /**
    * Get Name
    *
    * @return string
    */
   public function getName() {
      return $this->name;
   }
   /**
    * Set name
    *
    * @param string $name
    */
   public function setName($name) {
      $this->name = $name;
   }

   /**
    * Get LastName
    *
    * @return string
    */
   public function getLastName() {
      return $this->last_name;
   }
   /**
    * Set LastName
    *
    * @param string $lastname
    */
   public function setLastName($lastname) {
      $this->last_name = $lastname;
   }

   /**
    * Get Patronymic
    *
    * @return string
    */
   public function getPatronymic() {
      return $this->patronymic;
   }
   /**
    * Set Patronymic
    *
    * @param string $patronymic
    */
   public function setPatronymic($patronymic) {
      $this->patronymic = $patronymic;
   }

   /**
    * Get Birth
    *
    * @return string
    */
   public function getBirth() {
        return $this->birth;
   }
   /**
    * Set Birth
    *
    * @param string $birth
    */
   public function setBirth($birth) {
      $this->birth = $birth;
   }

   /**
    * Get vacancyId
    *
    * @return string
    */
   public function getVacancyId() {
      return $this->v_id;
   }
   /**
    * Set vacancyId
    *
    * @param string $vacancy_id
    */
   public function setVacancyId($vacancy_id) {
      $this->v_id = $vacancy_id;
   }
   
   /**
    * Get Email
    *
    * @return string
    */
   public function getEmail() {
      return $this->email;
   }
   /**
    * Set Email
    *
    * @param string $email
    */
   public function setEmail($email) {
      $this->email = $email;
   }
      
   /**
    * Get Phone
    *
    * @return string
    */
   public function getPhone() {
      return $this->phone;
   }
   /**
    * Set Phone
    *
    * @param string $phone
    */
   public function setPhone($phone) {
      $this->phone = $phone;
   }
   
   /**
    * Get Resume
    *
    * @return string
    */
   public function getResume() {
      return $this->resume;
   }
   /**
    * Set Resume
    *
    * @param string $resume
    */
   public function setResume($resume) {
      $this->resume = $resume;
   }
   
   /**
    * Get Status
    *
    * @return string
    */
   public function getStatus() {
      return $this->status;
   }
   /**
    * Set Status
    *
    * @param string $status
    */
   public function setStatus($status) {
      $this->status = $status;
   }
   
   /**
    * Get Number
    *
    * @return string
    */
   public function getNumber() {
      return $this->number;
   }
   /**
    * Set Number
    *
    * @param string $number
    */
   public function setNumber($number) {
      $this->number = $number;
   }

}