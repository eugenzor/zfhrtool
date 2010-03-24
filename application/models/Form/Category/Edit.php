<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма редактирования категорий
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_Category_Edit extends Zend_Form
{
    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function init() {

//        $this -> removeDecorator( 'HtmlTag' );
        $this->setAction ( $this->getView()->
            url ( array ('controller'=>'category', 'action' => 'edit') ) );
        $this->setMethod ( 'post' );

        $nameText = $this->createElement ( 'text', 'categoryName' );
        $nameText -> setLabel ( 'Название категории :' );
        $nameText -> setRequired ( true );
        $nameText -> setErrorMessages ( array ( '' ) );
        $nameText -> setErrorMessages ( array (
                '' ) );
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

        $submit = $this -> createElement ( 'submit', 'save', array (
                'label' => 'Сохранить' ) );
        $this->addElement ( $submit );
    }
}