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
            $vacancies = $objVacancies->fetchAll();
            //echo "dump: " . var_dump($vacancies);
            $this->view->vacancies = $objVacancies->fetchAll();
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
                    $objVacancy = $objVacancies -> getObjectById( $vacancyId );
                    if (! $objVacancy instanceof Vacancy) {
                        $objVacancy = $objVacancies -> createRow();
                    }

                    $objVacancy -> name = $form -> Name -> getValue();
                    $objVacancy -> num = $form -> Num -> getValue();
                    $objVacancy -> duties = $form -> Duties -> getValue();
                    $objVacancy -> requirements = $form -> Requirements -> getValue();
                    $objVacancy -> save();

                    $this -> _helper -> redirector ( 'index', 'vacancy' );
                }
            } else {
                $vacancyId = ( int ) $this->getRequest()->getParam('vacancyId');
                if ($vacancyId != '')
                {
                    // выбираем из базы данные о редактируемой вакансии
                    $vacancies = new Vacancies( );
                    $objVacancy = $vacancies->getObjectById( $vacancyId );

                    if ($objVacancy instanceof Vacancy) {
                        $this -> view -> objVacancy = $objVacancy;
                        $form -> populate(
                            array( 'Name'   =>  $objVacancy -> name,
                                   'Num' =>  $objVacancy -> num,
                                   'Duties' =>  $objVacancy -> duties,
                                   'Requirements' =>  $objVacancy -> requirements,
                                   'vacancyId'     =>  $objVacancy -> id) );
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
            $vacancy = $objVacancies->getObjectById($this->_request->getParam('vacancyId'));
            if (!($vacancy instanceof Vacancy)){
                throw new Zend_Exception('Error while deleting vacancy.');
            }
            $vacancy->delete();
            $this->_helper->redirector( 'index', 'vacancy' );
        }
    }
}