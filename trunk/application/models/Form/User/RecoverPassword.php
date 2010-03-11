<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма восстановления пароля
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_User_RecoverPassword extends Zend_Form
{
    /**
         * Инициализация формы, установка элементов
         *
     * @see Zend_Form::init()
     * @return void
     */
    public function init() {
        $this->setAction ( $this->getView()->url ( array (
                'controller' => 'user',
                'action' => 'recover-password' ), 'default' ) );
        $this->setMethod ( 'post' );

        $password = $this->createElement ( 'password', 'password' );
        $password->setLabel ( '[LS_FORM_FIELD_PASSWORD]' );
        $password->addValidator ( 'stringLength', false, array (
                6 ) );
        $password->setRequired ( true );
        $password->setErrorMessages ( array (
                '[LS_VALIDATION_PASSWORD_RULE]' ) );
        $password->setDescription ( '[LS_VALIDATION_PASSWORD_HINT]' );
        $this->addElement ( $password );


        $captcha = new Zend_Form_Element_Captcha ( 'captcha', array (
                'label' => "[LS_FORM_FIELD_CAPTCHA]",
                'captcha' => array (
                        'captcha' => 'Image',
                        'WordLen' => 6,
                        'GcFreq' => 100,
                        'font' => APPLICATION_PATH . '/../fonts/arial.ttf',
                        'ImgDir' => APPLICATION_PATH . '/../public/images/captcha/',
                        'ImgUrl' => $this->getView()->baseUrl() . '/images/captcha/',
                        'Expiration' => 300 ) ) );
        $this->addElement ( $captcha );

        $csrf = $this->createElement ( 'hash', 'csrf', array (
                'salt' => 'unique' ) );
        $csrf->setErrorMessages ( array (
                '[LS_VALIDATION_CSRF_FAILED]' ) );
        $this->addElement ( $csrf );

        $submit = $this->createElement ( 'submit', 'submit', array (
                'label' => '[LS_FORM_BUTTON_SUBMIT]' ) );
        $submit->setAttrib('class', 'button');
        $this->addElement ( $submit );
    }
}