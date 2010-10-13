<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма добавления/редактирования теста
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_Test_Edit extends Zend_Form
{
    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function init() {

        $this->setAction ( $this->getView()->
            url ( array ('controller'=>'test', 'action' => 'edit' ) ) );
        $this->setMethod ( 'post' );
        $this->setAttrib('id', 'questionForm');

        $testName = $this->createElement ( 'text', 'testName' );
        $testName -> setLabel ( '[LS_FORM_FILTER_TEXT_LABEL]' );
        $testName -> setRequired ( true );
        $testName -> addDecorator('Errors');
        $testName -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $testName );

        $categorySelect = $this -> createElement( 'select', 'categoryId' );
        $categorySelect -> setLabel( '[LS_FORM_FILTER_SELECT_LABEL]' );
        $categorySelect -> addMultiOptions( array('-1' => 'Все категории'));
        $this -> addElement($categorySelect);

        $testTime = $this->createElement ( 'text', 'testTime' );
        $testTime -> setLabel ( '[LS_FORM_TIME_TEXT_LABEL]' );
        $testTime -> setRequired ( true );
        $testTime -> addDecorator('Errors');
        $testTime -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $testTime );

        $testQuestionAmount = $this -> createElement( 'hidden',
            'testQuestionAmount' );
        $testQuestionAmount -> addFilter('Digits');
        $this -> addElement( $testQuestionAmount );

        $testId = $this -> createElement( 'hidden', 'testId' );
        $this -> addElement( $testId );

        $formAction = $this -> createElement( 'hidden', 'formAction' );
        $formAction -> setAttrib( 'id', 'formAction' );
        $this -> addElement( $formAction );

        $submit = $this -> createElement ( 'submit', 'save', array (
                'label' => '[LS_FORM_SUBMMIT_SAVE]' ) );
        $this->addElement ( $submit );

        $submitAddQuest = $this -> createElement ( 'submit', 'addQuestion',
            array ( 'label' => '[LS_FORM_SUBMMIT_ADD_QUESTION]' ) );
        $submitAddQuest -> setAttrib( 'onclick',
            'return SetAction(\'questionAdd\');' );
        $this->addElement ( $submitAddQuest );
    }

    /**
     * Установка элементов options для select
     *
     * @param  array $arrOptions
     * @return void
     */
    public function setSelectOptions(array $arrOptions)
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