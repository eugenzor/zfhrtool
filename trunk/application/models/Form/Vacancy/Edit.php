<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма редактирования вакансий
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_Vacancy_Edit extends Zend_Form
{
    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function init() {
//        $translator = new Zend_Translate('tmx', Zend_Registry::get('config')->resources->translate->errors, 'ru');
//        $this->setTranslator($translator);
//        $this -> removeDecorator( 'HtmlTag' );
        $this->setAction ( $this->getView()->
            url ( array ('controller'=>'vacancy', 'action' => 'edit') ) );
        $this->setMethod ( 'post' );

        $nameText = $this->createElement ( 'text', 'Name' );
        $nameText -> setLabel ( 'Название вакансии :' );
        $nameText -> setRequired ( true );
        $nameText -> addDecorator('Errors');
        $nameText -> addValidator('Db_NoRecordExists', true, array('vacancies', 'v_name'));
        $nameText -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $nameText );

        $numberText = $this->createElement ( 'text', 'Number' );
        $numberText -> setLabel ( 'Количество вакансий :' );
        $numberText -> setRequired ( true );
        $numberText -> addDecorator('Errors');
        $numberText -> addValidator('Int', true);
        $numberText -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $numberText );

        $dutiesTextArea = $this->createElement ( 'textarea', 'Duties' );
        $dutiesTextArea -> setLabel ( 'Обязаности :' );
        $dutiesTextArea -> setAttrib( 'rows', 5 );
        $dutiesTextArea -> setAttrib( 'cols', 50 );
        $dutiesTextArea -> setRequired ( true );
        $dutiesTextArea -> AddValidator( 'StringLength', true, array(0, 2000));
        $dutiesTextArea -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $dutiesTextArea );

        $requirementsTextArea = $this->createElement ( 'textarea', 'Requirements' );
        $requirementsTextArea -> setLabel ( 'Требования :' );
        $requirementsTextArea -> setAttrib( 'rows', 5 );
        $requirementsTextArea -> setAttrib( 'cols', 50 );
        $requirementsTextArea -> setRequired ( true );
        $requirementsTextArea -> AddValidator( 'StringLength', true, array(0, 2000));
        $requirementsTextArea -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $requirementsTextArea );

        $vacancyId = $this -> createElement( 'hidden', 'vacancyId' );
        $this -> addElement( $vacancyId );

        $submit = $this -> createElement ( 'submit', 'save', array (
                'label' => 'Сохранить' ) );
        $this->addElement ( $submit );
    }
}