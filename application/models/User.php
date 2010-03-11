<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Пользователь (строка таблицы users)
 *
 * @package zfhrtool
 * @subpackage Model
 */
class User extends Zend_Db_Table_Row_Abstract {
   const STATUS_ACTIVE = 'active';
   const STATUS_BLOCKED = 'blocked';
   const STATUS_VERIFYING = 'verifying';

   /**
    * Get Id
    *
    * @return string
    */
   public function getId() {
      return $this->id;
   }

   /**
    * Get Nickname
    *
    * @return string
    */
   public function getNickname() {
      return $this->nickname;
   }
   /**
    * Set Nickname
    *
    * @param string $nickname
    */
   public function setNickname($nickname) {
      $this->nickname = $nickname;
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
    * Get Password
    *
    * @return string
    */
   public function getPassword() {
      return $this->password;
   }
   /**
    * Set Password
    *
    * @param string $password
    */
   public function setPassword($password) {
      $this->password = $password;
   }
   /**
    * Get account status
    *
    * @return string
    */
   public function getStatus() {
      return $this->status;
   }
   /**
    * Set account status
    *
    * @param string $state
    */
   public function setStatus($status) {
      $this->status = $status;
   }
   /**
    * Get Role
    *
    * @return string
    */
   public function getRole() {
      return $this->role;
   }
   /**
    * Set Role
    *
    * @param string $role
    */
   public function setRole($role) {
      $this->role = $role;
   }
   /**
    * Get last login time
    *
    * @return Zend_Date
    */
   public function getLastLoginAt() {
      return new Zend_Date( $this->last_login_at, Zend_Date::ISO_8601 );
   }
   /**
    * Set last login time
    *
    * @param Zend_Date
    */
   public function setLastLoginAt(Zend_Date $lastLoginAt = null) {
      if (is_null( $lastLoginAt )) {
         $lastLoginAt = Zend_Date::now();
      }
      $this->last_login_at = $lastLoginAt->get( 'YYYY-MM-dd HH:mm:ss' );
   }

   /**
    * @see Db_Table_Row_Abstract::toString()
    *
    * @return string
    */
   public function toString() {
      return $this->getNickname();
   }
   /**
    * Magic
    *
    * @return string
    */
   public function __toString() {
      return $this->toString();
   }
}