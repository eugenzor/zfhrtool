<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма редактирования категорий вопросов теста
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_QuestionCategory_Edit extends Zend_Form
{
    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function init() {

        $this->setAction ( $this->getView()->
            url ( array ('controller'=>'questioncategory', 'action' => 'edit'), null, true ) );
        $this->setMethod ( 'post' );

        $nameText = $this->createElement ( 'text', 'categoryName' );
        $nameText -> setLabel ( 'Название категории :' );
        $nameText -> setRequired ( true );
        $nameText -> addDecorator('Errors');
        $nameText -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $nameText );

        $desrTextArea = $this->createElement ( 'textarea', 'categoryDescr' );
        $desrTextArea -> setLabel ( 'Комментарии :' );
        $desrTextArea -> setAttrib( 'rows', 3 );
        $desrTextArea -> setAttrib( 'cols', 50 );
        $desrTextArea -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $desrTextArea );

        $categoryId = $this -> createElement( 'hidden', 'categoryId' );
        $this -> addElement( $categoryId );

        $testId = $this -> createElement( 'hidden', 'testId' );
        $this -> addElement( $testId );

        $submit = $this -> createElement ( 'submit', 'save', array (
                'label' => 'Сохранить' ) );
        $this->addElement ( $submit );
    }
}