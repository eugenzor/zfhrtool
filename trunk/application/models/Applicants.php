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
     * Row Class
     * @var string
     */
    protected $_rowClass = 'Applicant';

    /**
     * Get Applicant By ApplicantId
     *
     * @param string $applicantId
     * @return Applicant | boolean
     */
    public function getApplicantById($applicantId)
    {
        $where = array (
                'id=?' => $applicantId );
        $objApplicant = $this->fetchRow ( $where );
        if (is_null ( $objApplicant )) {
            return false;
        }
        return $objApplicant;
    }

    /**
     * Get array of Applicants By vacancyId and status
     *
     * @param int $vacancyId
     * @param string $status
     * @return arrTest | array 
     */
    public function getApplicants($vacancyId = -1, $status = -1)
    {
        $select = $this -> getAdapter()-> select();
        $select -> from( $this->_name );
        if ( -1 != $vacancyId ) {
            $select -> where( $this->_name . '.v_id = ?', $vacancyId );
        }
        if ( -1 != $status ) {
            $select -> where( 'status = ?', $status );
        }
        $select -> join( 'vacancies', ' vacancies.v_id = applicants.v_id' );

        $stmt = $this -> getAdapter() -> query($select);
        $arrApplicants = $stmt -> fetchAll(Zend_Db::FETCH_OBJ);

        if (is_null ( $arrApplicants )) {
            return false;
        }
        return $arrApplicants;
    }

    /**
     * Removes Applicant By ApplicantId
     *
     * @param int $applicantId
     * @return void
     */
    public function removeApplicantById($applicantId)
    {
        // Удаляем информацию о тесте из БД
        $where = array (
                'id=?' => $applicantId );
        $this -> delete( $where );
        // @todo Надо удалить файл фото из ФС
    }
}