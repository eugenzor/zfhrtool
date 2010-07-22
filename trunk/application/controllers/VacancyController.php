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

             // выбираем из базы данные о тестах
            $objVT = new VacanciesTest();
            $tests = $objVT->getTestsV();
            $tests = $this->convertArr($tests, 'vacancy_id', true);
            $this->view->tests = $tests;
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
            // выбираем из базы данные о редактируемой вакансии            

            $objTest = new Tests();
            $tests = $objTest->fetchAll()->toArray();

            $objVT = new VacanciesTest();

            //Добавляет тесты на форму
            $form->addElementsForm($tests);
                    
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
                    $idSaveVacancy = $objVacancy -> save();

                    $test = $this->keyReplace($form->getValues(), 'test_');
                    $objVT->delete('vacancy_id = ' . $idSaveVacancy);
                    foreach ($test as $testId => $val) {
                        if ($val) {
                            $newRow = $objVT->createRow(array('vacancy_id' => $idSaveVacancy,
                                        'test_id' => $testId));
                            $newRow->save();
                        }
                    }
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

                    $testIds = $objVT->getTestIds($vacancyId);

                    // отмечаем выбранные тесты
                    foreach ($testIds as $id) {
                        $form->populate(
                                array('test_' . $id => 1));
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
            try {
                if (!($vacancy instanceof Vacancy)){
                    throw new Zend_Exception('Error while deleting vacancy.');
                }
                $vacancy->delete();
            }
            catch (Exception $ex) {
                $this -> view -> error = $ex-> getMessage();
            }
            $this -> _forward( 'index', 'vacancy' );
        }
    }

    /**
     * Возвращает массив ключом которого есть значение $key
     *
     * @param array $arr массив.
     * @param string $key значение которое нужно установить в качестве ключа.
     * @param bool $many повторения $key
     * @return array массив
     *
     * Пример:
     * ////////////
     * // $arr = array ("0" => array('id' =>'val0_id',
     * //                            'id2'=>'val0_id2'
     * //                            ),
     * //               "1" => array('id' =>'val1_id',
     * //                            'id2'=>'val1_id2'
     * //                            ),
     * //               "3" => array('id' =>'val3_id',
     * //                            'id2'=>'val3_id2'
     * //                            ));
     * // $key = 'id'
     * // $many = false
     * //
     * // return array ("val0_id" => array('id' =>'val0_id',
     * //                                  'id2'=>'val0_id2'
     * //                                  ),
     * //               "val1_id" => array('id' =>'val1_id',
     * //                                  'id2'=>'val1_id2'
     * //                                  ),
     * //               "val3_id" => array('id' =>'val3_id',
     * //                                  'id2'=>'val3_id2'
     * //                                   ));
     * /////////////////
     * // $arr = array ("0" => array('id' =>'val0_id',
     * //                            'id2'=>'val0_id2'),
     * //               "1" => array('id' =>'val0_id',
     * //                            'id2'=>'val0_id2'),
     * //               "3" => array('id' =>'val3_id',
     * //                            'id2'=>'val3_id2'));
     * // $key = 'id'
     * // $many = true
     * // return array ("val0_id" => array('0' => array('id' =>'val0_id',
     * //                                               'id2'=>'val0_id2'
     * //                                               ),
     * //                                  "1" => array('id' =>'val0_id',
     * //                                               'id2'=>'val0_id2'
     * //                                               )),
     * //               "val3_id" => array('0' => array('id' =>'val3_id',
     * //                                               'id2'=>'val3_id2'
     * //                                               )));
     * //////////////////
     */
    public function convertArr(array $arr, $key = 'id', $many = false)
    {
        $res = array();
        if ($many){
            foreach($arr as $a)
                $res[$a[$key]][] = $a;
        } else {
            foreach($arr as $a)
                $res[$a[$key]] = $a;
        }
        return (array) $res;
    }

    /**
     * Возвращает массив в котором вырезаны части ключей и удалены элементы в которых ключ не содержит $subkey
     * Пример:
     * keyReplace(array ('name_1' => 'val1', 'name_2' => 'val2', 'type' => 'val3'), 'name_')
     * Результат:
     * array ('1' => 'val1', '2' => 'val2')
     *
     * @param array $arr массив.
     * @param string $subkey что нужно вырезать из части ключей.
     * @return array массив
     */
    public function keyReplace(array $arr, $subkey) {
        $res = array();
        foreach ($arr as $key => $a)
            if (strpos($key, $subkey) === 0)
                $res[str_replace($subkey, '', $key)] = $a;
        return (array) $res;
    }
}