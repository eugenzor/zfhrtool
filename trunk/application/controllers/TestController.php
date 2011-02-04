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
                    $objTest->setTime (
                        $objForm -> testTime -> getValue() );
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
                            $arrQuestionCategories = $objTests ->
                                getQuestionCategoriesListByTestId( $testId );

                            $this -> view -> arrQuestion = $arrQuestion;
                            $this -> view -> arrQuestionCategories = $arrQuestionCategories;
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

                        $arrQuestionCategories = $objTests ->
                            getQuestionCategoriesListByTestId( $testId );

                        $this -> view -> arrQuestion = $arrQuestion;
                        $this -> view -> arrQuestionCategories = $arrQuestionCategories;
                        $this -> view -> testId = $testId;
                        
                        $objForm -> populate(
                            array( 'testName'           => $objTest -> t_name,
                                   'categoryId'         => $objTest -> cat_id,
                                   'testTime'           => $objTest -> time,
                                   'testQuestionAmount' => sizeof( $arrQuestion ),
                                   'testId'             => $objTest -> t_id));
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
        
        $objForm = new Form_Test_Testing();

        $link = $this->getRequest()->getParam('link');

        $objApplicantTests = new ApplicantTests ();
        $applicantTest = $objApplicantTests ->getTest($link);
        if (empty ($applicantTest)) exit;
        $applicantId = $applicantTest -> applicant_id;
        $testId = $applicantTest -> test_id;
        $applicantTestId = $applicantTest->id;
        $applicantScore = $applicantTest -> score;
        $applicantPercent = $applicantTest -> percent;

        $objTests = new Tests();
        $test = $objTests -> find($testId) -> current() -> toArray();
        $testName = $test['t_name'];
        $testTime = $test['time'];

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
            //получаем ответы на вопросы и сохраняем
            if ($objForm->isValid($_POST)) {

                $objApplicantAnswers = new ApplicantAnswers();

                $newAnswers = $this->keyReplace($objForm->getValues(), 'answer_');
                foreach ($newAnswers as $answerId => $val) {
                    if ($val) {
                        $newAnswer = $objApplicantAnswers->createRow(array('applicant_tests_id' => $applicantTestId,
                                                                  'answer_id' => $answerId));
                        $newAnswer->save();
                    } else {
                        unset( $newAnswers[$answerId] );
                    }
                }
                $result = $this -> calcTestScore($questions, $answers, $newAnswers); 
                $applicantTest -> score = $result['score'];
                $applicantTest -> percent = $result['percent'];
                $applicantTest->save();

                $this->view->sendTest = true;
                return;
            }
        } else {
            $this->view->testName = $testName;
            $this->view->applicantName = $applicantName;
            $this->view->time = $testTime;
            $this->view->score = $applicantScore;
            $this->view->percent = $applicantPercent;

            if (is_null($applicantTest -> date)) {
                // выводим тест //
                $this->view->objForm = $objForm;
                $applicantTest -> date = new Zend_Db_Expr('NOW()');
                $applicantTest->save();
            } else if ( $this -> _authorize( 'test', 'view')){
                // выводим результаты теста //
                $objApplicantAnswers = new ApplicantAnswers();

                $applicantAnswers = $objApplicantAnswers->getAnswers($applicantTestId);
                $applicantAnswers = $this->convertArr($applicantAnswers,'answer_id');
                
                $questions = $this-> makeQAArray($questions, $answers, $applicantAnswers);
                unset( $answers );
                unset( $applicantAnswers );
                
                $questionCategories = $objTests -> 
                    getQuestionCategoriesListByTestId($testId);

                if ( $questionCategories ) {
                    $questionCategories = $this -> 
                       calcCategoriesScore ( $questions, $questionCategories );
                }

                $this->view->countQuestions = $countQuestions;
                $this->view->countQuestionFail = $this->countWrongAnswers($questions);
                $this->view->questionsAndAnswers = $questions;
                $this->view->questionCategories = $questionCategories;
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
     * Пересчитывает результат теста (вместо процентов - баллы)
     * @return void
     */
    public function recalcAction()
    {
        if ( $this -> _authorize( 'test', 'edit')) {
            $link = $this->getRequest()->getParam('link');

            $objApplicantTests = new ApplicantTests ();
            $applicantTest = $objApplicantTests ->getTest($link);
            if (empty ($applicantTest)) exit;
            $applicantId = $applicantTest -> applicant_id;
            $testId = $applicantTest -> test_id;
            $applicantTestId = $applicantTest->id;

            $objQuestion = new Questions();
            $questions = $objQuestion->getQuestions($testId);
            $questions = $this->convertArr($questions, 'tq_id');

            $objTestAnswers = new Answers();
            $answers = $objTestAnswers->getAnswers(array_keys($questions));
            // ключем $answers будет id вопроса
            $answers = $this->convertArr($answers, 'tq_id', true);
            
            $objApplicantAnswers = new ApplicantAnswers();
            $applicantAnswers = $objApplicantAnswers->getAnswers($applicantTestId);
            $applicantAnswers = $this->convertArr($applicantAnswers,'answer_id');
            
            $result = $this -> calcTestScore($questions, $answers, $applicantAnswers); 
            $applicantTest -> score = $result['score'];
            $applicantTest -> percent = $result['percent'];
            $applicantTest->save();
        }
        $this->_helper->redirector ( 'testing', 'test', null, array('link' => $link) );            
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
    
    /**
     * Возвращает массив состоящий из вопросов теста / ответов соискателя вида:
     * array('id'=>array(                      // id - идентификатор вопроса
     *                  'text'     => string,  // текст вопроса
     *                  'category' => int,     // категория вопроса
     *                  'score'    => float    // полученные баллы за вопрос
     *                  'weight'   => int      // максимальное кол-во баллов за вопрос  
     *                  'state'    => int,     // 0 - неправильно отвечен
     *                                         // 1 - частично правильно отвечен
     *                                         // 2 - правильно отвечен
     *                  'answers'  => array(   // массив ответов
     *                                         // заполняется при 'status' > 0
     *                       'text'  => string // текст варианта ответа
     *                       'flag'  => int    // правильный/неправильный вариант
     *                       'state' => int    // 0 - вариант не выбран
     *                                         // 1 - вариант выбран
     *                             )
     *                  )
     * @param array $questions        - массив вопросов
     * @param array $answers          - массив ответов
     * @param array $applicantAnswers - массив ответов соискателя               
     * @return array
     */
    public function makeQAArray(array $questions, array $answers, array $applicantAnswers)
    {
        $result = array();
        //Перебираем все вопросы
        foreach ($questions as $questionId => $question) {
            $questionIndex = $question['tq_sort_index'];
            $result[ $questionIndex ]['text'] = $question['tq_text'];
            $result[ $questionIndex ]['category'] = $question['tqc_id'];
            $result[ $questionIndex ]['weight'] = $question['tq_weight'];
            $questionAnswers = array();
            $answerId = 0;
            $wrongAnswer = 0;    //метка неверного ответа
            $answerWeight = $question['tq_weight'] / $question ['tq_right_answers_amount'];
            $questionScore = 0;
            //Перебираем все варианты ответов на этот вопрос
            foreach ($answers[$questionId] as $answer) {
                $questionAnswers[$answerId]['text'] = $answer['tqa_text'];
                $questionAnswers[$answerId]['flag'] = $answer['tqa_flag'];
                $answerState = (int) isset( $applicantAnswers[$answer['tqa_id']] );
                $questionAnswers[$answerId]['state'] = $answerState;
                if ( $answer['tqa_flag'] == '1' ) {  //Вариант правильный
                    $questionScore += $answerWeight * $answerState;
                } else {                            //Вариант неправильный
                    $wrongAnswer += $answerState;
                }
                $answerId ++;
            }
            $questionScore = round( $questionScore, 2 );
            $result[ $questionIndex ]['score'] = $questionScore;
            if ( $questionScore == $question['tq_weight'] ) {
                //вопрос отвечен верно
                $result[$questionIndex]['state'] = 2;
            } else {
                //вопрос отвечен частично верно
                $result[$questionIndex]['state'] = 1;
                $result[$questionIndex]['answers'] = $questionAnswers;
            }
            if ( $wrongAnswer ||  ! $questionScore ) {
                //вопрос отвечен неверно
                $result[$questionIndex]['state'] = 0;
                $result[$questionIndex]['answers'] = $questionAnswers;
                $result[ $questionIndex ]['score'] = 0;
            }
        }
        return $result;
    }
    
    /**
     * Возвращает кол-во неверных ответов
     * 
     * @param array $questions	-	массив вопросов
     * @return int
     */
    public function countWrongAnswers(array $questions)
    {
        $result = 0;
        foreach ( $questions as $question ) {
            if ( ! $question['state'] ) {
                $result ++;
            }
        }
        return $result;
    }
    
    /** Подсчитывает процент правильных ответов в каждой категории.
     *  Возвращает массив вида:
     *  array ( 'имя_категории' => int )
     *  
     * @param array $questions - массив вопросов, возвращенный makeQAArray()
     * @param array $categories - массив категорий
     * @return array
     */
    public function calcCategoriesScore(array $questions, array $categories) 
    {
        $score = array_fill_keys( array_keys( $categories ), 0 );
    	foreach ( $questions as $question ) {
    	    if ( $question['category'] ){
    	        if ( $score[$question['category']] ) {
    	            $score[$question['category']] = 
    	                ($score[$question['category']] + $question['score']/$question['weight'])/2;
    	        } else {
    	            $score[$question['category']] = $question['score']/$question['weight'];
    	        }
    	    }
    	}
    	foreach ($score as $key => $percent) {
    	    $result[ $categories[$key] ] = round( $percent*100 );
    	}
    	return $result;
    }
    
    /**
     * Возвращает результат прохождения теста в виде массива
     *  array( 'score'   => float,  // сумма баллов, набранных соискателем в тесте
     *         'percent' => int )   // процент от максимального кол-ва баллов
     * 
     * @param array $questions	-	массив вопросов теста
     * @param array $answers - массив ответов теста
     * @param array $newAnswers - массив ответов соискателя
     * @return array
     */
    public function calcTestScore(array $questions, array $answers, array $newAnswers)
    {
        $applicantScore = 0;
        $maxScore = 0;
        foreach ($questions as $questionId => $question) {
            $maxScore += $question['tq_weight'];
            $answerWeight = $question['tq_weight']/$question['tq_right_answers_amount'];
            $questionScore = 0;
            foreach ($answers[$questionId] as $answerId => $answer) {
                if ($answer['tqa_flag']) {
                    if ( isset( $newAnswers[ $answer['tqa_id'] ] ) ) {
                        $questionScore += $answerWeight;
                    }
                } else {
                    if ( isset( $newAnswers[ $answer['tqa_id'] ] ) ) {
                        $questionScore = 0;
                        break;
                    }
                }
            }
        $applicantScore += round($questionScore, 2);
        }
        $percent = round( $applicantScore / $maxScore * 100 );
        return array( 'score' => $applicantScore, 'percent' => $percent); 
    }       
 
}