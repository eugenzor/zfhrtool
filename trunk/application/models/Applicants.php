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
    public function getApplicants($vacancyId = -1, $status = -1, $order = '')
    {
        $select = $this -> getAdapter()-> select();
        $select -> from( $this->_name );
        if ( -1 != $vacancyId ) {
            $select -> where( $this->_name . '.v_id = ?', $vacancyId );
        }
        if ( -1 != $status ) {
            $select -> where( 'applicants.status = ?', $status );
        }
        $select -> join( 'vacancies', ' vacancies.v_id = applicants.v_id' );
        
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
     * Removes Applicant By ApplicantId
     *
     * @param int $applicantId
     * @return void
     */
    public function removeApplicantById($applicantId)
    {
        
        // Удаляем информацию о соискателе из БД
        $where = array (
                'id=?' => $applicantId );
        $this -> delete( $where );
        
        // Удаляем комменты о соискателе из БД
        $Comments = new Comments();
        $Comments -> removeCommentsByApplicantId($applicantId);
        
        // Удаляем фото        
        $validator = new Zend_Validate_File_Exists($_SERVER['DOCUMENT_ROOT'] . '/public/images/photos/');
        if ($validator -> isValid($applicantId . '.jpg'))
            unlink($_SERVER['DOCUMENT_ROOT'] . '/public/images/photos/' . $applicantId . '.jpg');
    }
}