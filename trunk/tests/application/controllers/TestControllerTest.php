<?php
/**
 * @package zfhrtool
 * @subpackage Test
 */

/**
 * Тесты системы управления тестами
 *
 * Тестирование контроллера отвещающего за вывод / редактирования / удалние тестов
 * @package zfhrtool
 * @subpackage Test
 */
class TestControllerTest extends Zht_Test_PHPUnit_ControllerTestCase
{

    /**
     * Prepares the environment before running a test.
     */

    protected function setUp() {
        $this -> setDbDump( dirname(__FILE__) . '/_files/setup.sql' );
        parent::setUp ();
    }


    protected function _doLogin( $email, $password )
    {
        $auth = Auth::getInstance ();
        $result = $auth ->
            authenticate ( new Auth_Adapter ( $email, $password ) );
    }

    // проверяем что данный экшен доступен
    public function testIndexAction()
    {
//            $this -> _testSignin();
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('index');
    }

    // проверяем поведение экшена по умолчанию
    public function testDefaultIndexAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('index');
        $this->assertResponseCode(200);

        // на странице должен присутствовать элемент form в количестве 1 шт.
        $this->assertQueryCount('form', 1);

        // на странице должен присутствовать элемент с id="categoryId" в количестве 1 шт.
        $this->assertQueryCount('#categoryId', 1);

        // на странице должен присутствовать элемент с id="strTestFilter" в количестве 1 шт.
        $this->assertQueryCount('#strTestFilter', 1);

        // на странице должен присутствовать элемент с id="tableTest" в количестве 1 шт.
        $this->assertQueryCount('#tableTest', 1);
    }

    // проверяем поведение экшена при входящих параметрах
    public function testWithInputDataIndexAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'categoryId' => '2',
                'strTestFilter ' => 'p' ) );
        $this->dispatch('/test');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('index');
        $this->assertResponseCode(200);

        // на странице должен присутствовать элемент с id="categoryId" в количестве 1 шт.
        $this->assertQueryCount('#categoryId', 1);

        // на странице должен присутствовать элемент с id="strTestFilter" в количестве 1 шт.
        $this->assertQueryCount('#strTestFilter', 1);

        // на странице должен присутствовать элемент с id="tableTest" в количестве 1 шт.
        $this->assertQueryCount('#tableTest', 1);
    }

    // проверяем что данный экшен доступен
    public function testEditAction()
    {
//            $this -> _testSignin();
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/edit');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('edit');
    }

    public function testAddingEditAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/edit');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('edit');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#testName', 1);
        $this->assertQueryCount('#testId', 1);
    }

    public function testEditedEditAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/edit/testId/3');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('edit');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#testName', 1);
        $this->assertQueryCount('#testId', 1);
    }

    public function testUpdateValidEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'testName' => 'PHP (ООП) (ed)',
                'testTime' => 20,
                'categoryId' => '2',
                'testQuestionAmount' => '3',
                'testId' => '3' ));
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/edit');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('edit');
        $this->assertRedirect('/test');
    }

    public function testUpdateInValidEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'testName' => '',
                'categoryId ' => '2',
                'testQuestionAmount' => '3'));
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/edit/testId/3');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('edit');
        $this->assertQueryCount('dd#testName-element ul.errors', 1);
    }

    public function testQuestionAddButtonEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'testName' => 'PHP (ООП) (ed)',
                'testTime' => 20,
                'categoryId' => '2',
                'testQuestionAmount' => '3',
                'formAction' => 'questionAdd',
                'testId' => '3' ));
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/edit');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('edit');
        $this->assertRedirect('/question/edit/testId/3');
    }

    public function testQuestionAddButtonWithNewTestEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'testName' => 'PHP (ООП) (ed)',
                'testTime' => 20,
                'categoryId' => '2',
                'testQuestionAmount' => '3',
                'formAction' => 'questionAdd'));
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/edit');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('edit');
        $this->assertRedirect('/question/edit/testId/9');
    }

    // проверяем что данный экшен доступен
    public function testRemoveAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/remove/testId/3');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('index');
    }
    

    public function testViewTestingAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/testing/link/016960a3b0f7e1bd75ce8fe4b7057e89');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('testing');
    }

    public function testNewTestingAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/testing/link/92c2bc7eb520710774a9d2963c0899f7');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('testing');
    }

    public function testSendTestingAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'answer_67' => 0,
                'answer_68' => 1,
                'answer_47' => 0,
                'answer_48' => 0,
                'answer_49' => 0,
                'answer_54' => 0,
                'answer_55' => 0,
                'answer_69' => 0,
                'answer_70' => 0,
                'answer_49' => 0,));
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/testing/link/92c2bc7eb520710774a9d2963c0899f7');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('testing');
    }

    public function testNewLinkAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/newLink/applicantId/16/testId/1');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('newLink');
    }

    public function testRecalculationAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/recalculation/testId/1');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('recalculation');
    }
    
    //проверяем работоспособность єкшена
    public function testRecalcAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/test/recalc/link/92c2bc7eb520710774a9d2963c0899f7');
        $this->assertModule('default');
        $this->assertController('test');
        $this->assertAction('recalc');
        $this->assertRedirect('/test/recalc/link/92c2bc7eb520710774a9d2963c0899f7');        
    }

    protected $questions = array(
            '333' => array(
                'tq_weight'               => '1',
                'tq_right_answers_amount' => '1',
                'tq_id'                   => '333',
                'tq_sort_index'           => '0',
                'tq_text'                 => 'question1',
                'tqc_id'                  => '1'
            ),
            '444' => array(
                'tq_weight'               => '2',
                'tq_right_answers_amount' => '2',
                'tq_id'                   => '444',
                'tq_sort_index'           => '1',
                'tq_text'                 => 'question2',
                'tqc_id'                  => '2'
            ),
        );

    protected $answers = array(
            '333' => array(
                '0' => array(
                    'tqa_flag' => '1',
                    'tqa_id'   => '10',
                    'tqa_text' => 'answer10'
                ),
                '1' => array(
                    'tqa_flag' => '0',
                    'tqa_id'   => '11',
                    'tqa_text' => 'answer11'
                )
            ),
            '444' => array(
                '0' => array(
                    'tqa_flag' => '0',
                    'tqa_id'   => '20',
                    'tqa_text' => 'answer20'
                ),
                '1' => array(
                    'tqa_flag' => '1',
                    'tqa_id'   => '21',
                    'tqa_text' => 'answer21'
                ),
                '2' => array(
                    'tqa_flag' => '1',
                    'tqa_id'   => '22',
                    'tqa_text' => 'answer22'
                )
            )
        );

    //тестирование функции calcTestScore
    /**
     * @dataProvider provider
     */
    public function testCalcTestScore($score, $testanswers)
    {
        $result = TestController::calcTestScore($this->questions, $this->answers, $testanswers);
        $this->assertEquals($score, $result);
    }
    
    public function provider()
    {
        return array(
            //Все ответы правильные
            array(3.0, array(
                        '10' => array('id' => 110),
                        '21' => array('id' => 121),
                        '22' => array('id' => 122),
                     )
            ),
            //Все ответы не правильные
            array(0.0, array(
                        '20' => array('id' => 020),
                        '21' => array('id' => 121),
                        '22' => array('id' => 122),
                     )
            ),
            //Первый не правильный, второй правильный
            array(2.0, array(
                        '11' => array('id' => 011),
                        '21' => array('id' => 121),
                        '22' => array('id' => 122),
                     )
            ),
            //Первый не правильный, второй частично правильный
            array(1.0, array(
                        '11' => array('id' => 011),
                        '22' => array('id' => 122),
                     )
            ),            
        );
    }
    
    //тестирование функции makeQAArray
    public function testMakeQAArray()
    {
        $expectation = array(
            '0' => array(
                'text'     => 'question1',
                'category' => '1',
                'score'    => 1.0,
                'state'    => 2
            ),
            '1' => array(
                'text'     => 'question2',
                'category' => '2',
                'score'    => 1.0,
                'state'    => 1,
                'answers'  => array(
                    '0'  => array(
                        'text'  => 'answer20',
                        'flag'  => '0',
                        'state' => '0'
                    ),  
                    '1'  => array(
                        'text'  => 'answer21',
                        'flag'  => '1',
                        'state' => '1'
                    ),  
                    '2'  => array(
                        'text'  => 'answer22',
                        'flag'  => '1',
                        'state' => '0'
                    ),  
                )
            ),
        );
        $testanswers = array(
            '10' => array('id' => 110),
            '21' => array('id' => 121),
        );
        $result = TestController::makeQAArray( $this->questions, $this->answers, $testanswers );
        $this->assertEquals( $expectation, $result );
    }
    
    public function testCalcCategoriesScore()
    {
        $questions = array(
            '1' => array(
                'category' => '1',
                'score'    => 1.5
            ),
            '2' => array(
                'category' => '2',
                'score'    => 0.5
            ),
            '3' => array(
                'category' => '1',
                'score'    => 1.0
            ),
            '4' => array(
                'category' => '2',
                'score'    => 1.0
            )
        );
        $category = array('1' => 'category1', '2' => 'category2');
        $expectation = array('category1' => 2.5, 'category2' => 1.5);
        $this->assertEquals( $expectation,
                             TestController::calcCategoriesScore($questions,
                                                                 $category));
    }
}