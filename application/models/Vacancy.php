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

// TODO здесь куча ненужных методов, которые просто перепрописывают свойства
// через методы, что уже и так сделано в методах __set и __get зендовских классов
// Единственное чем может быть полезен этот запрос, что он "исправляет"
// Префиксы полей. Но если мы их уберем, то и надобность в этом отпадет
// Все должно быть направлено на минимизацию времени разработки.
// А написание этих методов - тупая механическая работа.
class Vacancy extends Zend_Db_Table_Row_Abstract {
   /**
    * Get Id
    *
    * @return string
    */
   public function getId() {
      return $this->v_id;
   }

    /**
     * Set Id
     *
     * @param string $id
     */
    public function setId($id) {
       $this->v_id = $id;
    }

   /**
    * Get Name
    *
    * @return string
    */
   public function getName() {
      return $this->v_name;
   }
   /**
    * Set name
    *
    * @param string $name
    */
   public function setName($name) {
      $this->v_name = $name;
   }

   /**
    * Get Duties
    *
    * @return string
    */
   public function getDuties() {
      return $this->v_duties;
   }
   /**
    * Set Duties
    *
    * @param string $duties
    */
   public function setDuties($duties) {
      $this->v_duties = $duties;
   }

   /**
    * Get Requirements
    *
    * @return string
    */
   public function getRequirements() {
      return $this->v_requirements;
   }
   /**
    * Set Requirements
    *
    * @param string $requirements
    */
   public function setRequirements($requirements) {
      $this->v_requirements = $requirements;
   }

   /**
    * Get Number
    *
    * @return string
    */
   public function getNum() {
      return $this->v_num;
   }
   /**
    * Set Number of vacancies
    *
    * @param string $duties
    */
   public function setNum($number) {
      $this->v_num = $number;
   }
}