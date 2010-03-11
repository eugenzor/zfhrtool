<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма авторизации пользователя
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_User_Signin extends Zend_Form
{
    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function init() {
        $this->setAction ( $this->getView()->url ( array ('action'=>'signin') ) );
        $this->setMethod ( 'post' );
        $this->setAttrib('class', 'signin');

        $email = $this->createElement ( 'text', 'email' );
        $email->setLabel ( '[LS_FORM_FIELD_EMAIL]' );
        $email->addValidator ( 'EmailAddress' );
        $email->setRequired ( true );
        $email->setErrorMessages ( array (
                '' ) );
        $this->addElement ( $email );

        $password = $this->createElement ( 'password', 'password' );
        $password->setLabel ( '[LS_FORM_FIELD_PASSWORD]' );
        $password->setRequired ( true );
        $password->setErrorMessages ( array (
                '' ) );
        $this->addElement ( $password );

        $csrf = $this->createElement ( 'hash', 'csrf', array (
                'salt' => 'unique' ) );
        $csrf->setErrorMessages ( array (
                '[LS_VALIDATION_CSRF_FAILED]' ) );
        $this->addElement ( $csrf );

        $submit = $this->createElement ( 'submit', 'signin', array (
                'label' => '[LS_FORM_BUTTON_SIGNIN]' ) );
        $submit->setAttrib('class', 'button');
        $this->addElement ( $submit );
    }
}