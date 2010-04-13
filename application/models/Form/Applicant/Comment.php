<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма комментирования соискателя
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_Applicant_Comment extends Zend_Form
{
    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function init() {
        $this->setAction ( $this->getView()->
            url ( array ('controller' => 'applicant', 'action' => 'show' ) ) );
        $this->setMethod ( 'post' );

        $applicantId = $this -> createElement( 'hidden', 'applicantId' );
        $applicantId -> addValidator( 'Db_RecordExists', false,
	    array('applicants', 'id')
	);
        $this -> addElement ( $applicantId );

        $Comment = $this->createElement ( 'textarea', 'Comment' );
        $Comment -> setLabel ( '[LS_FORM_STATUS_COMMENT_LABEL]' );
        $Comment -> setRequired ( true );
        $Comment -> setAttrib('cols', '50');
        $Comment -> setAttrib('rows', '5');
        $Comment -> addValidator('StringLength', true, array(1, 1000));
        $Comment -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $Comment );
        
	$Submit = $this -> createElement( 'submit', 'save' );
        $Submit -> setLabel ( '[LS_FORM_SUBMMIT_SAVE]' );
        $this -> addElement ( $Submit );
    }
}
?>