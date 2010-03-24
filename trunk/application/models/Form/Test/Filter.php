<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма фильтрации тестов
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_Test_Filter extends Zend_Form
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
            url ( array ('controller'=>'test') ) );
        $this->setMethod ( 'post' );
        $this->setAttrib('class', 'filter-form');

        $categorySelect = $this -> createElement( 'select', 'categoryId' );
        $categorySelect -> setLabel( '[LS_FORM_FILTER_SELECT_LABEL]' );
        $categorySelect -> addMultiOptions( array('-1' => 'Все категории'));
        $categorySelect -> setAttrib( 'onchange', 'this.form.submit();' );
        $this -> addElement($categorySelect);

        $filterText = $this->createElement ( 'text', 'strTestFilter' );
        $filterText -> setLabel ( '[LS_FORM_FILTER_TEXT_LABEL]' );
        $filterText -> setRequired ( true );
        $filterText -> setErrorMessages ( array ( '' ) );
//        $filterText -> removeDecorator( 'HtmlTag' );
        $this -> addElement ( $filterText );

        $submit = $this -> createElement ( 'submit', 'filter', array (
                'label' => '[LS_FORM_SUBMMIT_FILTER]' ) );
//        $submit -> removeDecorator( 'HtmlTag' );
        $this->addElement ( $submit );
    }

    /**
     * Установка элементов options для select
     *
     * @param  array $arrOptions
     * @return void
     */
    public function setFilterSelectOptions(array $arrOptions)
    {
        if ( !empty($arrOptions))
        {
            $arrFormattedOptions = array();
            foreach ($arrOptions as $arrOption)
            {
                $arrFormattedOptions[ $arrOption[ 'cat_id' ] ] =
                        $arrOption['cat_name'];
            }
            $categorySelect = $this -> getElement( 'categoryId' );
            $categorySelect -> addMultiOptions ( $arrFormattedOptions );
        }
    }
}