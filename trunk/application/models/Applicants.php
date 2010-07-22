<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Таблица соискателей
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Applicants extends Zht_Db_Table
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'applicants';

    /**
     * Имя таблицы, которое изпользуеться при join
     * @var string
     */
    const  NAME = 'applicants';

    /**
     * Row Class
     * @var string
     */
    protected $_rowClass = 'Applicant';

    /**
     * Get array of Applicants By vacancyId and status
     *
     * @param int $vacancyId
     * @param string $status
     * @return arrTest | array 
     */
    public function getApplicants($vacancyId = -1, $status = -1, $order = '')
    {
        $select = $this -> getAdapter()-> select();
        $select -> from( $this->_name );
        if ( -1 != $vacancyId ) {
            $select -> where( $this->_name . '.vacancy_id = ?', $vacancyId );
        }
        if ( -1 != $status ) {
            $select -> where( 'applicants.status = ?', $status );
        }
        $select -> join(
            'vacancies',
            'vacancies.id = '
                . $this->_name
                . '.vacancy_id',
            array('name as v_name')
        );
        
        if ($order == 'Name') $order = 'last_name';
        elseif ($order == 'NameDesc') $order = 'last_name DESC';
        elseif ($order == 'Status') $order = 'status';
        elseif ($order == 'StatusDesc') $order = 'status DESC';
        elseif ($order == 'Vacancy') $order = 'v_name';
        elseif ($order == 'VacancyDesc') $order = 'v_name DESC';
        elseif ($order == 'Email') $order = 'email';
        elseif ($order == 'EmailDesc') $order = 'email DESC';
        else $order = '';
        
        if ($order != '')
            $select -> order($order);
            
        $stmt = $this -> getAdapter() -> query($select);
        $arrApplicants = $stmt -> fetchAll(Zend_Db::FETCH_OBJ);

        if (is_null ( $arrApplicants )) {
            return false;
        }
        return $arrApplicants;
    }

    /**
     * Возвращает имя Соискателя
     *
     * @param int $applicantId Id Соискателя
     * @return string
     */
    public function getName( $applicantId)
    {
        $applicant = $this -> find($applicantId) -> current();
        $name = "{$applicant->last_name} {$applicant->name} {$applicant->patronymic}";
        return $name;

    }
}