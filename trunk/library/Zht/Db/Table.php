<?php
/**
 * @package zfhrtool
 * @subpackage library
 */

/**
 * Дополнение Zend_Db_Table
 *
 * Класс дополняет стандартный Zend_Db_Table несколькими полезными методами
 * @package zfhrtool
 * @subpackage library
 */
class Zht_Db_Table extends Zend_Db_Table_Abstract
{

    /**
     * Получить линейный массив
     *
     * Возращает линейный массив ключ-значение подобно тому, как это делает метод
     * fetchPairs для объекта db
     * @return array
     */
    public function fetchPairs(){
        return $this->getAdapter()->fetchPairs("SELECT * FROM $this->_name");
    }

    /**
     * Получение строки таблицы по ее id
     * 
     * @param mixed $id
     * @return Zend_Db_Table_Row_Abstract | boolean
     */
    public function getObjectById($id) {
        $rowSet = $this->find($id);
        if($rowSet->count()>0) {
            return $rowSet->current();
        }
        return false;
    }


}