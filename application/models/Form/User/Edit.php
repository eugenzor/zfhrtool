<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма изменения статуса соискателя
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_User_Edit extends Zend_Form
{
    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function init() {
        $this->setAction ( $this->getView()->
            url ( array ('controller' => 'user', 'action' => 'edit' ) ) );
        $this->setMethod ( 'post' );

        $userId = $this -> createElement( 'hidden', 'userId' );
        $userId -> addValidator( 'Db_RecordExists', false,
	    array('users', 'id')
	);
        $this -> addElement ( $userId );

        $Email = $this->createElement ( 'text', 'Email' );
        $Email -> setLabel ( '[LS_FORM_EDIT_EMAIL_LABEL]' );
        $Email -> setRequired ( true );
        $Email -> addValidator('StringLength', true, array(3, 100));
        $Email -> addValidator('EmailAddress', true);
        $Email -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $Email );

        $Nickname = $this->createElement ( 'text', 'Nickname' );
        $Nickname -> setLabel ( '[LS_FORM_EDIT_NAME_LABEL]' );
        $Nickname -> setRequired ( true );
        //$Nickname -> addValidator('Alnum', true);
        $Nickname -> addValidator('StringLength', true, array(2, 150));
        $Nickname -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $Nickname );

	
        $Role = $this -> createElement( 'select', 'Role' );
        $Role -> setLabel( '[LS_FORM_ROLE_ROLE_LABEL]' );
        $Role -> addMultiOptions(
	    array(
		'guest' => '[LS_ROLE_GUEST]',
		'manager' => '[LS_ROLE_MANAGER]',
		'recruit' => '[LS_ROLE_RECRUIT]',
		'staff' => '[LS_ROLE_STAFF]',
		'dismissed' => '[LS_ROLE_DISMISSED]',
		'administrator' => '[LS_ROLE_ADMINISTRATOR]'
	    )
        );
        $this -> addElement ( $Role );

	$Submit = $this -> createElement( 'submit', 'save' );
        $Submit -> setLabel ( '[LS_FORM_SUBMMIT_SAVE]' );
        $this -> addElement ( $Submit );
    }
}
?>