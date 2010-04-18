<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Форма фильтрации соискателей
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Form_Applicant_Filter extends Zend_Form
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
            url ( array ('controller'=>'applicant') ) );
        $this->setMethod ( 'post' );
        $this->setAttrib('class', 'filter-form');
	$this->setAttrib('id', 'AFF');

        $vacancySelect = $this -> createElement( 'select', 'vacancyId' );
        $vacancySelect -> setAttrib( 'id', 'vacancyId' );
        $vacancySelect -> setLabel( 'Вакансия: ' );
        $vacancySelect -> addMultiOptions( array('-1' => 'Все вакансии'));
        $vacancySelect -> setAttrib( 'onchange', 'this.form.submit();' );
        $this -> addElement($vacancySelect);

        $statusSelect = $this -> createElement( 'select', 'status' );
        $statusSelect -> setLabel( 'Статус: ' );
        $statusSelect -> addMultiOptions(
	    array(
		'-1'		=> '[LS_STATUS_ALL]',
		'new' 		=> '[LS_STATUS_NEW]',
		'invited'	=> '[LS_STATUS_INVITED]',
		'interviewed'	=> '[LS_STATUS_INTERVIEWED]',
		'rejected'	=> '[LS_STATUS_REJECTED]',
		'taken'		=> '[LS_STATUS_TAKEN]',
		'staff'		=> '[LS_STATUS_STAFF]',
		'dismissed'	=> '[LS_STATUS_DISMISSED]',	
	    )
        );
        $statusSelect -> setAttrib( 'onchange', 'this.form.submit();' );
        $this -> addElement($statusSelect);
    }

    /**
     * Установка элементов options для select
     *
     * @param  array $arrOptions
     * @return void
     */
    public function setVacancies(array $arrOptions)
    {
        if ( !empty($arrOptions))
        {
            $arrFormattedOptions = array();
            foreach ($arrOptions as $arrOption)
            {
                $arrFormattedOptions[ $arrOption[ 'id' ] ] =
                        $arrOption['name'];
            }
            $vacancySelect = $this -> getElement( 'vacancyId' );
            $vacancySelect -> addMultiOptions ( $arrFormattedOptions );
        }
    }
}