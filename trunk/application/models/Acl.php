<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * 
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

        // Систма тестов (для юзера с правами manager-a)
        $this -> addRole( new Zend_Acl_Role( 'manager' ), 'staff' );

        $this -> add( new Zend_Acl_Resource( 'test' ));
        $this -> add( new Zend_Acl_Resource( 'category' ));
        $this -> add( new Zend_Acl_Resource( 'question' ));
        $this -> allow( 'manager' , 'test', array( 'view', 'edit', 'remove' ));
        $this -> allow( 'manager' , 'category',
            array( 'view', 'edit', 'remove' ));
        $this -> allow( 'manager' , 'question',
            array( 'edit', 'remove', 'up', 'down' ));
    }
}