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
     * Удалает комментарии о соискателе и его фото 
     *
     * @return void
     */
    public function _delete() {
        // Удаляем комменты о соискателе из БД
        $Comments = new Comments();
        $Comments -> removeCommentsByApplicantId($this->id);
        
        // Удаляем фото  
        $applicantId = $this->id;   
        $validator = new Zend_Validate_File_Exists($_SERVER['DOCUMENT_ROOT'] . '/public/images/photos/');
        if ($validator -> isValid($applicantId . '.jpg'))
            unlink($_SERVER['DOCUMENT_ROOT'] . '/public/images/photos/' . $this -> id . '.jpg');
    }
}
