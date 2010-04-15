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
                    // Выполняем update (insert/update данных о вакансии)
                    $objVacancies = new Vacancies();

                    $vacancyId = $form -> vacancyId -> getValue();
                    if ( !empty($vacancyId)) {
                            $objVacancy = $objVacancies ->
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
                    $objVacancy -> setNum ( $Number );
                    $objVacancy -> setDuties ( $Duties );
                    $objVacancy -> setRequirements ( $Requirements );
                    $objVacancy -> save();

//                    $this->_helper->redirector ( 'index', 'vacancy' );
                    $this->_forward('index', 'vacancy');
                }
            } else {
                $vacancyId = ( int ) $this->getRequest()->getParam('vacancyId');
                if ($vacancyId != '')
                {
                    // выбираем из базы данные о редактируемой вакансии
                    $vacancies = new Vacancies( );
                    $objVacancy = $vacancies->getVacancyById( $vacancyId );

                    if ($objVacancy) {
                        $this -> view -> objVacancy = $objVacancy;
                        $form -> populate(
                            array( 'Name'   =>  $objVacancy -> v_name,
                                   'Number' =>  $objVacancy -> v_num,
                                   'Duties' =>  $objVacancy -> v_duties,
                                   'Requirements' =>  $objVacancy -> v_requirements,
                                   'vacancyId'     =>  $objVacancy -> v_id) );
                    }
                }
            }
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