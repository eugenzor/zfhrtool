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
class Form_User_Signup extends Zend_Form {
    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function init() {
        $this->setAction ( $this->getView()->url ( array ()) );
        $this->setMethod ( 'post' );

        $nickname = $this->createElement ( 'text', 'nickname' );
        $nickname->setLabel ( '[LS_FORM_FIELD_NICKNAME]' );
        $nickname->addValidator ( 'stringLength', false, array (
                3,
                150 ) );
        $nickname->setRequired ( true );
        $nickname->setErrorMessages ( array (
                '[LS_VALIDATION_NICKNAME_RULE]' ) );
        $nickname->setDescription ( '[LS_VALIDATION_NICKNAME_HINT]' );
        $this->addElement ( $nickname );

        $email = $this->createElement ( 'text', 'email' );
        $email->setLabel ( '[LS_FORM_FIELD_EMAIL]' );
        $email->addValidator ( 'EmailAddress', false );
        $email->setRequired ( true );
        $email->setErrorMessages ( array (
                '[LS_VALIDATION_EMAIL_RULE]' ) );
        $email->setDescription ( '[LS_VALIDATION_EMAIL_HINT]' );
        $this->addElement ( $email );

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
                        'WordLen' => 4,
                        'GcFreq' => 100,
                        'font' => APPLICATION_PATH . '/../fonts/arial.ttf',
                        'ImgDir' => APPLICATION_PATH . '/../public/images/captcha/',
                        'ImgUrl' => $this->getView()->baseUrl() . '/images/captcha/',
                        'Expiration' => 300 ) ) );
        //      $captcha->setErrorMessages(array('[LS_VALIDATION_CAPTCHA_RULE]'));
        $captcha->setDescription ( '[LS_VALIDATION_CAPTCHA_HINT]' );
        $this->addElement ( $captcha );


        $submit = $this->createElement ( 'submit', 'signup', array (
                'label' => '[LS_FORM_BUTTON_SIGNUP]' ) );
        $submit->setAttrib('class', 'button');
        $this->addElement ( $submit );
    }
}