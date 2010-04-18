<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма добавления/редактирования соискателя
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_Applicant_Edit extends Zend_Form
{
    /**
     * Инициализация формы, установка элементов
     *
     * @see Zend_Form::init()
     * @return void
     */
    public function init() {        
        $this->setAction ( $this->getView()->
            url ( array ('controller' => 'applicant', 'action' => 'edit' ) ) );
        $this->setMethod ( 'post' );
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $LastName = $this->createElement ( 'text', 'LastName' );
        $LastName -> setLabel ( '[LS_FORM_EDIT_LASTNAME_LABEL]' );
        $LastName -> setRequired ( true );
        $LastName -> addValidator('Alpha', true);
        $LastName -> addValidator('StringLength', true, array(2, 25));
        $LastName -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $LastName );

        $Name = $this->createElement ( 'text', 'Name' );
        $Name -> setLabel ( '[LS_FORM_EDIT_NAME_LABEL]' );
        $Name -> setRequired ( true );
        $Name -> addValidator('Alpha', true);
        $Name -> addValidator('StringLength', true, array(2, 25));
        $Name -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $Name );

        $Patronymic = $this->createElement ( 'text', 'Patronymic' );
        $Patronymic -> setLabel ( '[LS_FORM_EDIT_PATRONYMIC_LABEL]' );
        $Patronymic -> setRequired ( true );
        $Patronymic -> addValidator('Alpha', true);
        $Patronymic -> addValidator('StringLength', true, array(2, 25));
        $Patronymic -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $Patronymic );

        $Birth = new Zend_Dojo_Form_Element_DateTextBox('Birth',
            array(
                'required'  =>  true,
                'label' => '[LS_FORM_EDIT_BIRTH_LABEL]'
            )
        );
        $Birth -> setDatePattern('dd.MM.yyyy');
        $Birth -> addValidator('Date');
        $this -> addElement ( $Birth );

        $vacancySelect = $this -> createElement( 'select', 'VacancyId' );
        $vacancySelect -> setLabel( '[LS_FORM_EDIT_VACANCY_LABEL]' );
        $this -> addElement($vacancySelect);

        $Email = $this->createElement ( 'text', 'Email' );
        $Email -> setLabel ( '[LS_FORM_EDIT_EMAIL_LABEL]' );
        $Email -> setRequired ( true );
        $Email -> addValidator('StringLength', true, array(3, 100));
        $Email -> addValidator('EmailAddress', true);
        $Email -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $Email );

        $Phone = $this->createElement ( 'text', 'Phone' );
        $Phone -> setLabel ( '[LS_FORM_EDIT_PHONE_LABEL]' );
        $Phone -> setRequired ( true );
        $Phone -> addValidator('StringLength', true, 10);
        $Phone -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $Phone );

        $Resume = $this->createElement ( 'textarea', 'Resume' );
        $Resume -> setLabel ( '[LS_FORM_EDIT_RESUME_LABEL]' );
        $Resume -> setRequired ( true );
        $Resume -> setAttrib('cols', '50');
        $Resume -> setAttrib('rows', '5');
        $Resume -> addValidator('StringLength', true, array(1, 10000));
        $Resume -> addFilters(array('StringTrim', 'HtmlEntities'));
        $this -> addElement ( $Resume );

        $Photo = $this -> createElement( 'file', 'Photo' );
        $Photo -> setLabel( '[LS_FORM_EDIT_PHOTO_LABEL]' );
        $Photo -> setMaxFileSize(1024000);
        $Photo -> addValidator('Count', false, 1);
        $Photo -> addValidator('Size', false, 1024000);
        $Photo -> addValidator('Extension', false, 'jpg,png,gif');
        $Photo -> setDestination(UPLOAD_PATH);
        $this -> addElement ( $Photo );

        $applicantId = $this -> createElement( 'hidden', 'applicantId' );
        $this -> addElement( $applicantId );
        
        $submit = $this -> createElement ( 'submit', 'save', array (
                'label' => '[LS_FORM_SUBMMIT_SAVE]' ) );
        $this->addElement ( $submit );
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
                $arrFormattedOptions[ $arrOption[ 'id' ] ] =
                        $arrOption['name'];
            }
            $vacancySelect = $this -> getElement( 'VacancyId' );
            $vacancySelect -> addMultiOptions ( $arrFormattedOptions );
        }
    }

    /**
     * показывает поле для номера штатного сотрудника
     *
     * @return void
     */
    public function showNumber()
    {
        $Number = $this->createElement ( 'text', 'Number' );
        $Number -> setLabel ( '[LS_FORM_EDIT_NUMBER_LABEL]' );
        $Number -> addValidator('Int', true);
        $this -> addElement ( $Number );

        $this -> removeElement('save');
        $submit = $this -> createElement ( 'submit', 'save', array (
                'label' => '[LS_FORM_SUBMMIT_SAVE]' ) );
        $this->addElement ( $submit );
    }
    
}