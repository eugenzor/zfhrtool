<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Вакансия (строка таблицы)
 *
 * @package zfhrtool
 * @subpackage Model
 */


class Vacancy extends Zend_Db_Table_Row_Abstract {
    /**
     * Удаляет вакансию из БД, если нет соискателей по такой вакансии
     *
     * @return void
     */
   public function _delete() {
      $Applicants = new Applicants();
      $res = $Applicants -> getApplicants( $this->id );
      if ( $res != false) {
         throw new Zend_Exception ( '[LS_VACANCY_HAS_APPLICANTS]' );
      }
   }
}