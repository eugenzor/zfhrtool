<?php
/**
 * @package zfhrtool
 * @subpackage Controller
 */


/**
 * Контроллер тестов
 *
 * Обеспечивает работу с тестами
 * @package zfhrtool
 * @subpackage Controller
 */
class TestController extends Controller_Action_Abstract
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
     * Список тестов (главная страница)
     * @return void
     */
    public function indexAction()
    {
        if ( $this -> _authorize( 'test', 'view')) {
            $objFilterForm = new Form_Test_Filter();
            $objCategories = new Categories ();
            $arrCategory = $objCategories -> getCategoryList();
            $objFilterForm -> setFilterSelectOptions( $arrCategory );

            if ($this->getRequest ()->isPost ()) {
                   $arrParams = $this -> _request -> getPost();
                   $categoryId = ( int ) $arrParams['categoryId'];
                   $strTestFilter = !empty( $arrParams['strTestFilter'] )?
                        strip_tags( trim ( $arrParams['strTestFilter'] ) ) : null;
                   $objFilterForm -> populate( $arrParams );
            } else {
                $categoryId = -1;
                $strTestFilter = null;
            }

            $objTests = new Tests();
            $arrTest = $objTests ->
                getTestListByCategoryId($categoryId, $strTestFilter);

            $this -> view -> arrTest = $arrTest;
            $this -> view -> objFilterForm = $objFilterForm;
        }
    }

    /**
     * Добавление/обновление теста
     * @return void
     */
    public function editAction()
    {
        if ( $this -> _authorize( 'test', 'edit')) {
            $objForm = new Form_Test_Edit();
            $objCategories = new Categories ();
            $arrCategory = $objCategories -> getCategoryList();
            $objForm -> setSelectOptions( $arrCategory );

            if ($this->getRequest ()->isPost ()){
                if ( $objForm->isValid ( $_POST )) {
                    // Выполняем update (insert/update данных о категории)
                    $objTests = new Tests ();

                    $testId = $objForm -> testId -> getValue();
                    if ( !empty($testId)) {
                        $objTest = $objTests -> getTestById( $testId );
                    } else {
                        $testId = null;
                        $objTest = $objTests -> createRow();
                    }

                    $testName = $objForm -> testName -> getValue();
                        $objTest->setName ( $testName );
                    $objTest->setCategoryId (
                        $objForm -> categoryId -> getValue() );
                    $objTest->setQuestionAmount ( ( int )
                        $objForm -> testQuestionAmount -> getValue() );
                    $objTest -> save();

                    if ( 'questionAdd' == $objForm -> formAction -> getValue() ) {
                        if (!$testId) {
                            $testId = $objTests -> getAdapter()-> lastInsertId();
                        }
                        $this->_helper -> redirector ( 'edit', 'question', null,
                            array( 'testId' => $testId ) );
                    } else {
                        $this->_helper->redirector ( 'index', 'test' );
                    }
                } else {
                    $testId = $this->getRequest()->getParam('testId');
                    if ($testId != '')
                    {
                        // выбираем из базы данные о редактируемом тесте
                        $objTests = new Tests ( );
                        $objTest = $objTests->getTestById( $testId );

                        if ($objTest) {
                            $arrQuestion = $objTests ->
                                getQuestionListByTestId( $testId );

                            $this -> view -> arrQuestion = $arrQuestion;
//                            $this -> view -> testId = $testId;
                        }
                    }
                }
            } else {
                $testId = $this->getRequest()->getParam('testId');
                if ($testId != '')
                {
                    // выбираем из базы данные о редактируемом тесте
                    $objTests = new Tests ( );
                    $objTest = $objTests->getTestById( $testId );

                    if ($objTest) {
                        $arrQuestion = $objTests ->
                            getQuestionListByTestId( $testId );

                        $this -> view -> arrQuestion = $arrQuestion;
                        $this -> view -> testId = $testId;
                        $objForm -> populate(
                            array( 'testName'           => $objTest -> t_name,
                                   'categoryId'         => $objTest -> cat_id,
                                   'testQuestionAmount' => sizeof( $arrQuestion ),
                                    'testId'            => $objTest -> t_id));
                    }
                }
            }
            $this -> view -> objForm = $objForm;
        }
    }

    /**
     * Удаление  теста
     * @return void
     */
    public function removeAction()
    {
        if ( $this -> _authorize( 'test', 'remove')) {
            $objTests = new Tests ();

            $arrParams = $this -> getRequest() -> getParams();

            if (array_key_exists( 'testId', $arrParams ) &&
                    !empty( $arrParams['testId'] ) ) {
                $objTests -> removeTestById( ( int ) $arrParams['testId'] );
            }

            $this->_forward ( 'index', 'test' );
        }
    }

    /**
     * Создание link для прохождения теста
     * @return void
     */
    public function newlinkAction()
    {
        if ( $this -> _authorize( 'test', 'edit')) {
            $applicantId = (int) $this -> getRequest() -> getParam('applicantId');
            $testId = (int) $this -> getRequest() -> getParam('testId');
            $objApplicantTests = new ApplicantTests();
            //$objVacanciesTest = new VacanciesTest();
            if ( ! $objApplicantTests -> existLink($applicantId, $testId)) {
                $newRow = $objApplicantTests -> createRow();
                $newRow -> applicant_id = $applicantId;
                $newRow -> test_id = $testId;
                $newRow -> link = md5(time());
                $newRow -> save();
            }
            $this->_helper->redirector ( 'index', 'applicant' );
        }
    }

    /**
     * Тестирование
     * @return void
     */
    public function testingAction()
    {        
        if ( $this -> _authorize( 'test', 'view')) {
            $objForm = new Form_Test_Testing();

            $link = $this->getRequest()->getParam('link');

            $objApplicantTests = new ApplicantTests ();
            $applicantTest = $objApplicantTests ->getTest($link);
            if (empty ($applicantTest)) exit;
            $applicantId = $applicantTest -> applicant_id;
            $testId = $applicantTest -> test_id;
            $applicantTestId = $applicantTest->id;

            $objTests = new Tests();
            $test = $objTests -> find($testId) -> current() -> toArray();
            $testName = $test['t_name'];

            $objApplicants = new Applicants();
            $applicantName = $objApplicants -> getName($applicantId);            

            $objQuestion = new Questions();
            $questions = $objQuestion->getQuestions($testId);
            $questions = $this->convertArr($questions, 'tq_id');
            $countQuestions = count($questions);

            $objTestAnswers = new Answers();
            $answers = $objTestAnswers->getAnswers(array_keys($questions));
            $answers = $this->convertArr($answers, 'tq_id', true); // ключем $answers будет id вопроса

            $objForm->addElementsForm($questions, $answers);

            if ($this->getRequest()->isPost()) {
                if ($objForm->isValid($_POST)) {
                    
                    $objApplicantAnswers = new ApplicantAnswers();
                    
                    $newAnswers = $this->keyReplace($objForm->getValues(), 'answer_');
                    foreach ($newAnswers as $answerId => $val) {
                        if ($val) {
                            $newAnswer = $objApplicantAnswers->createRow(array('applicant_tests_id' => $applicantTestId,
                                                                      'answer_id' => $answerId));
                            $newAnswer->save();
                        }
                    }

                    // Вычисляем количество правильных ответов на вопросы
                    $questionOk = 0;
                    foreach ($answers as $arr) {
                        $ok = true;
                        foreach ($arr as $answer){
                            if($answer['tqa_flag'] != $newAnswers[$answer['tqa_id']]){
                                $ok = false;
                                break;
                            }
                        }
                        if($ok) {
                            $questionOk++;
                        }
                    }
                    $percent = round(100 * $questionOk / $countQuestions);

                    $applicantTest -> percent = $percent;
                    $applicantTest->save();

                    $this->_helper->redirector('index', 'applicant');
                }
            } else {
                $this->view->testName = $testName;
                $this->view->applicantName = $applicantName;

                if (is_null($applicantTest -> date)) {
                    $this->view->objForm = $objForm;
                    $applicantTest -> date = date('Y.m.d H:i:s');
                    $applicantTest->save();
                } else {
                    $objApplicantAnswers = new ApplicantAnswers();

                    $applicantAnswers = $objApplicantAnswers->getAnswers($applicantTestId);
                    $applicantAnswers = $this->convertArr($applicantAnswers,'answer_id');

                    // Вычисляем количество правильных ответов на вопросы
                    // ключем $answers будет id вопроса
                    $questionOk = 0;
                    $failAnswerQuestions = array();
                    foreach ($answers as $id => $arr) {
                        $ok = true;
                        foreach ($arr as $answer){
                            if( (bool) $answer['tqa_flag'] != isset($applicantAnswers[$answer['tqa_id']])){
                                $ok = false;
                                $failAnswerQuestions[$questions[$id]['tq_sort_index']] = $questions[$id]; // $id == $an['tq_id']
                                ksort($failAnswerQuestions);                                
                                // ключами $failAnswerQuestions будут tq_sort_index
                                break;
                            }
                        }
                        if($ok) {
                            $questionOk++;
                        }
                    }
                    ksort($failAnswerQuestions);
                    // ключи $failAnswerQuestions будут отсортировыны по tq_sort_index

                    $applicantAnswers = $this->convertArr($applicantAnswers,'tq_id',true);

                    $this->view->failAnswerQuestions = $failAnswerQuestions;
                    $this->view->newRows = $applicantAnswers;
                    $this->view->countQuestions = $countQuestions;
                    $this->view->countQuestionFail = $countQuestions - $questionOk;
                }
            }
        }
    }

    /**
     * Пересчитывает количество вопросов в тесте
     * @return void
     */
    public function recalculationAction()
    {
        if ( $this -> _authorize( 'test', 'edit')) {
            $testId = (int) $this -> getRequest() -> getParam('testId');
            if ($testId) {
                $objTests = new Tests();
                $objTests -> recalculationQuestions($testId);
                $objQuestions = new Questions();
                $objQuestions -> recalculationAnswers($testId);
            //die(''.$testId);
            }
            $this->_helper->redirector ( 'index', 'test' );
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