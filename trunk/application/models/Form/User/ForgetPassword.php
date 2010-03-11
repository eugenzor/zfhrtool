<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма напоминания пароля
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_User_ForgetPassword extends Zend_Form
{
    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function init() {
        $this->setAction ( $this->getView()->url ( array ('controller'=>'user', 'action'=>'forget-password') ) );
        $this->setMethod ( 'post' );

        $email = $this->createElement ( 'text', 'email' );
        $email->setLabel ( '[LS_FORM_FIELD_EMAIL]' );
        $email->addValidator ( 'EmailAddress' );
        $email->setRequired ( true );
        $email->setErrorMessages ( array (
                '[LS_VALIDATION_EMAIL_RULE]' ) );
        $this->addElement ( $email );

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