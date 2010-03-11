<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма регистрации пользователя
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Acl extends Zend_Acl
{
    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function __construct()
    {
        $this->addRole ( new Zend_Acl_Role ( 'guest' ) );
        $this->addRole ( new Zend_Acl_Role ( 'recruit' ), 'guest'  );
        $this->addRole ( new Zend_Acl_Role ( 'staff' ), 'guest'  );
        $this->addRole ( new Zend_Acl_Role ( 'dismissed' ), 'guest'  );
        $this->addRole ( new Zend_Acl_Role ( 'administrator' ), 'guest' );

        $this->add ( new Zend_Acl_Resource ( 'autharea' ) );


        // Guest acl
        $this->allow ( 'guest', 'autharea', array (
                'signin',
                'signup' ) );



        $this->allow ( 'administrator' );
    }
}
