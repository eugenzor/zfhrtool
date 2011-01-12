<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма добавления/редактирования вопроса
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_Question_Edit extends Zend_Form
{
    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function init()
    {

//        $this -> setIsArray( false );
        $this->setAction ( $this->getView()->
            url ( array ('controller'=>'question', 'action' => 'edit' ) ) );
        $this->setMethod ( 'post' );

        $questionTextArea = $this->createElement ( 'textarea', 'questionText' );
        $questionTextArea -> setLabel ( 'Текст вопроса :' );
        $questionTextArea -> setAttrib( 'rows', 3 );
        $questionTextArea -> setAttrib( 'cols', 50 );
        $questionTextArea -> addFilters(array('StringTrim'));
        $questionTextArea -> setRequired( true );
        $this -> addElement ( $questionTextArea );
        
        $categorySelect = $this -> createElement( 'select', 'categoryId' );
        $categorySelect -> setLabel( '[LS_FORM_CATEGORY_SELECT_LABEL]' );
        $this -> addElement($categorySelect);

        $questionWeight = $this->createElement ( 'select', 'questionWeight' );
        $questionWeight -> setLabel ( '[LS_FORM_QUESTION_WEIGHT_LABEL]' );
        $questionWeight -> addMultiOptions(array(1 => '1', '2', '3', '4'));
        $this -> addElement ( $questionWeight );
        
        if ( $this)
        
        $testId = $this -> createElement( 'hidden', 'testId' );
        $this -> addElement( $testId );
        
        $questionId = $this -> createElement( 'hidden', 'questionId' );
        $this -> addElement( $questionId );

        $this->addSubForm(new Zend_Form_SubForm, 'answer');

        $elemButton = $this -> createElement( 'button', 'Добавить' );
        $elemButton -> clearDecorators();
        $elemButton -> addDecorator('FormElements');
        $elemButton -> addDecorator('ViewHelper');
        $elemButton -> addDecorator( 'HtmlTag', array( 'tag' => 'div',
            'id'  => 'addAnswerButton'));
        $this -> addElement ( $elemButton );

        $submit = $this -> createElement ( 'submit', 'save', array (
                'label' => '[LS_FORM_SUBMMIT_SAVE]' ) );
        $this->addElement ( $submit );
    }

    /**
     * Добавление subform для вариантов ответов (динамически)
     *
     * @param  array $arrAnswer
     * @return void
     */
    public function addAnswersSubForm(array $arrAnswers)
    {
        $this -> addSubForm(new Zend_Form_SubForm, 'answer');

        $objSubForm = $this->getSubForm('answer');
        $objSubForm -> addPrefixPath('Form_Decorator', APPLICATION_PATH.'/models/Form/Decorator/', 'decorator');
        $objSubForm -> clearDecorators();
        $objSubForm -> addDecorator( 'AnswerHeader' );
        $objSubForm -> addDecorator('FormElements');
        $objSubForm -> addDecorator('HtmlTag', array('tag' => 'div', 'id'  => 'answerTable'));

        $objSubForm -> addDecorator('FormElements');
        $objSubForm -> addDecorator('HtmlTag', array('tag' => 'div', 'id'  => 'answerTable'));

        $i = 0;
        if ( !empty($arrAnswers))
        {
            foreach ($arrAnswers as $arrAnswer)
            {
                $i++;
                $objSubForm -> addSubForm(new Zend_Form_SubForm, ( string ) $i);

                $objInnerSubForm = $objSubForm -> getSubForm( ( string ) $i);
                $objInnerSubForm -> setDecorators( array( 'FormElements' ));
                $objInnerSubForm -> addDecorator( 'HtmlTag', 
                    array( 'tag' => 'div' ) );

                $elemTextArea = $this->createElement ( 'textarea', 'text' );
                $elemTextArea -> clearDecorators();
                $elemTextArea -> addDecorator('FormElements');
                $elemTextArea -> addDecorator('ViewHelper');


                $elemTextArea -> setAttrib( 'rows', 3 );
                $elemTextArea -> setAttrib( 'cols', 50 );
                $elemTextArea -> setValue( $arrAnswer[ 'tqa_text' ] );
                $elemTextArea -> addFilters(array('StringTrim'));
                $objInnerSubForm -> addElement ( $elemTextArea );

                $elemCheckBox = $this -> createElement( 'checkbox', 'flag' );
                $elemCheckBox -> clearDecorators();
                $elemCheckBox -> addDecorator('FormElements');
                $elemCheckBox -> addDecorator('ViewHelper');
                if ($arrAnswer[ 'tqa_flag' ]) {
                    $elemCheckBox -> setAttrib( 'checked', 'checked');
                }
                    
                $elemHidden = $this -> createElement( 'hidden', 'id' );
                $elemHidden -> setValue( $arrAnswer[ 'tqa_id' ]);
                $objInnerSubForm -> addElement( $elemHidden );
                $objInnerSubForm -> addElement ( $elemCheckBox );
            }
        } 
        $this -> getElement( 'Добавить' ) -> setAttrib( 'onclick',
        'addAnswer(' . $i . '); return true;' );
    }
     /**
     * Установка элементов options для селектора категорий)
     *
     * @param  array $arrOptions
     * @return void
     */
    public function setCategoriesSelectOptions($arrOptions)
    {
        if ( !empty($arrOptions))
        {
            $categorySelect = $this -> getElement( 'categoryId' );
            $categorySelect -> addMultiOptions ( $arrOptions );        
        } else {
            $this -> removeElement( 'categoryId' );
        }
    }
}