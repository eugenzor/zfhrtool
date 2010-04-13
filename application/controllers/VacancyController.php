<?php
/**
 * @package zfhrtool
 * @subpackage Controller
 */


/**
 * Контроллер вакансий
 *
 * Обеспечивает работу с категориями тестов
 * @package zfhrtool
 * @subpackage Controller
 */

class VacancyController extends Controller_Action_Abstract
{

    /**
     * Инициализация контроллера
     * @return void
     */
    public function init()
    {
        parent::init();
    }

    /**
     * Список вакансий (главная страница)
     * @return void
     */
    public function indexAction()
    {

        if ( $this -> _authorize( 'vacancies', 'view')) {
            $objVacancies = new Vacancies();
            $arrVacancies = $objVacancies -> getVacancies();

            $this -> view -> arrVacancies = $arrVacancies;
        }
    }

    /**
     * Добавление/обновление вакансии
     * @return void
     */
    public function editAction()
    {
        if ( $this -> _authorize( 'vacancies', 'edit')) {
            $form = new Form_Vacancy_Edit();
            if ($this->getRequest ()->isPost ()){
                if ( $form->isValid ( $_POST )) {
                    
                    echo str_replace("\n", "<br>", str_replace("\t", str_repeat("&nbsp;", 4), (string)$form));
                    
                    // Выполняем update (insert/update данных о вакансии)
                    $objVacancies = new Vacancies();

                    $vacancyId = $form -> vacancyId -> getValue();
                    if ( !empty($vacancyId)) {
                            $objVacancy = $objVacancy ->
                            getVacancyById( $vacancyId );
                        } else {
                            $objVacancy = $objVacancies -> createRow();
                        }

                    $Name = $form -> Name -> getValue();
                    $Number = $form -> Number -> getValue();
                    $Duties = $form -> Duties -> getValue();
                    $Requirements = $form -> Requirements -> getValue();
                    // trim и htmlEnteties делают фильтры zend_form
                    $objVacancy -> setName ( $Name );
                    $objVacancy -> setNumber ( $Number );
                    $objVacancy -> setDuties ( $Duties );
                    $objVacancy -> setRequirements ( $Requirements );
                    $objVacancy -> save();

                    $this->_redirect('vacancy');
                }
            } else {
                $vacancyId = ( int ) $this->getRequest()->getParam('vacancyId');
                if ($vacancyId != '')
                {
                    // выбираем из базы данные о редактируемой вакансии
                    $vacancies = new Vacancies( );
                    $objVacancy = $vacancies->getVacancies( $vacancyId );

                    if ($objVacancy) {
                        $this -> view -> objVacancy = $objVacancy;
                        $form -> populate(
                            array( 'v_name'   =>  $objVacancy -> v_name,
                                   'v_number' =>  $objVacancy -> v_num,
                                   'v_duties' =>  $objVacancy -> v_duties,
                                   'v_requrements' =>  $objVacancy -> v_requrements,
                                   'v_id'     =>  $objVacancy -> v_id) );
                    }
                }
            }
    //        print_r( $form->getErrors());
            //  @todo: НЕ выводит сообщения об ошибках в форму
            $this -> view -> objVacancyEditForm = $form;
        }
    }

    /**
     * Удаление вакансии
     * @return void
     */
    public function removeAction()
    {
        if ( $this -> _authorize( 'vacancies', 'remove')) {
            $objVacancies = new Vacancies();

            $arrParams = $this->getRequest()->getParams();

            if (array_key_exists('vacancyId', $arrParams) &&
                    !empty($arrParams['vacancyId'])) {
                $objVacancies -> removeVacancyById($arrParams['vacancyId']);
            }

            $this->_forward ( 'index', 'vacancy' );
        }
    }
}