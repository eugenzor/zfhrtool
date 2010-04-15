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
class Form_User_Role extends Zend_Form
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