<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Таблица вакансий
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Vacancies extends Zht_Db_Table
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'vacancies';

    /**
     * Row Class
     * @var string
     */
    protected $_rowClass = 'Vacancy';

    /**
     * Get Vacancy By vacancyId
     *
     * @param string $vacancyId
     * @return Category | boolean
     */
    // TODO Этот метод здесь лишний. В классе Zht_Db_Table есть метод
    // getObjectById, который делает тоже самое
    public function getVacancyById($vacancyId)
    {
        $where = array (
                'v_id=?' => $vacancyId );
        $objCategory = $this->fetchRow ( $where );
        if (is_null ( $objCategory )) {
            return false;
        }
        return $objCategory;
    }

    /**
     * Get Vacancy List (array)
     *
     * @return Vacancy | boolean
     */

    //TODO в Zend_Db_Table есть метод fetchAll который делает тоже самое
    public function getVacancies()
    {
        $arrCategory = $this -> getAdapter()->
            fetchAll("SELECT * FROM $this->_name");

        if (is_null ( $arrCategory )) {
            return false;
        }
        return $arrCategory;
    }

    /**
     * Remove Vacancy By Id
     *
     * @param string $vacancyId
     * @return boolean
     */

    // TODO в данной ситуации лучше воспользоваться стандартным методом
    // удаления объекта в классе Vacancy
    // Как это лучше сделать - я покажу в контроллере
    public function removeVacancyById($vacancyId)
    {
        $query = "SELECT count(id) from applicants "
               . "WHERE v_id = $vacancyId";
        $intTestAmount = $this -> getAdapter() ->fetchOne( $query );
        if ( $intTestAmount > 0 ) {
            throw new Exception ( '[LS_VACANCY_HAS_APPLICANTS]' );
            return false;
        }
        $where = array (
                'v_id=?' => $vacancyId );
        $intResult = $this -> delete( $where );
        return $intResult;
    }
}

