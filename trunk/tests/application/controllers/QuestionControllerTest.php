<?php
/**
 * @package zfhrtool
 * @subpackage Test
 */

/**
 * Тесты системы управления тестами
 *
 * Тестирование контроллера отвещающего за редактирования / удалние вопросов тестов
 * @package zfhrtool
 * @subpackage Test
 */
class QuestionControllerTest extends Zht_Test_PHPUnit_ControllerTestCase
{

    /**
     * Prepares the environment before running a test.
     */
/*
    protected function setUp() {
        $this -> setDbDump( dirname(__FILE__) . '/_files/setup.sql' );
        parent::setUp ();
    }
*/

    protected function _doLogin( $email, $password )
    {
        $auth = Auth::getInstance ();
        $result = $auth ->
            authenticate ( new Auth_Adapter ( $email, $password ) );
    }

    public function testAddingEditAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch("/question/edit/testId/1");
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertAction('edit');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#questionText', 1);
        $this->assertQueryCount('#testId', 1);
    }

    public function testEditingEditAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/edit/testId/1/questionId/1');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertAction('edit');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#questionText', 1);
        $this->assertQueryCount('#testId', 1);
    }

    public function testUpdateValidEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'questionText' => 'вопрос',
                'testId' => '1',
                'questionId' => '1',
                'answer' => array(
                                array(
                                    'text' => '1',
                                    'flag' => '0' ),
                                array(
                                    'text' => '2',
                                    'flag' => '1' ),
                                array(
                                    'text' => '3',
                                    'flag' => '0' ) ) ) );
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/edit');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertRedirect('test/edit/testId/1');
    }

    public function testUpdateInValidEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'testId' => '1',
                'questionId' => '1',
                'answer' => array(
                                array(
                                    'text' => '1',
                                    'flag' => '0' ),
                                array(
                                    'text' => '2',
                                    'flag' => '1' ),
                                array(
                                    'text' => '3',
                                    'flag' => '0' ) ) ) );
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/edit');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertAction('edit');
        $this->assertQueryCount('dd#questionText-element ul.errors', 1);
    }

    public function testUpdateValidWithoutQuestionIdEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'questionText'   => 'вопрос',
                'questionWeight' => '3',
                'testId'         => '1',
                'answer'         => array(
                                    array(
                                        'text' => '1',
                                        'flag' => '0' ),
                                    array(
                                        'text' => '2',
                                        'flag' => '1' ),
                                    array(
                                        'text' => '3',
                                        'flag' => '0' ) ) ) );
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/edit');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertRedirect('test/edit/testId/1');
    }

    public function testRemoveAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/remove/testId/1/questionId/1');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertRedirect('test/edit/testId/1');
    }

    public function testWithoutTestIdRemoveAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/remove/questionId/2');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertRedirect('test/');
    }

    public function testUpAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/up/testId/2/questionId/10');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertAction('up');
        $this->assertRedirect('test/edit/testId/2');
    }

    public function testWithoutTestIdUpAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        try {
            $this->dispatch('/question/up/questionId/9');
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), '[LS_REQUIRED_PARAM_FAILED]');
        }
    }

    public function testWithoutQuestionIdUpAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        try {
            $this->dispatch('/question/up/testId/2');
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), '[LS_REQUIRED_PARAM_FAILED]');
        }
    }

    public function testDownAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/down/testId/2/questionId/5');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertAction('down');
        $this->assertRedirect('test/edit/testId/2');
    }

    public function testWithoutTestIdDownAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        try {
            $this->dispatch('/question/down/questionId/5');
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), '[LS_REQUIRED_PARAM_FAILED]');
        }
    }

    public function testWithoutQuestionIdDownAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        try {
            $this->dispatch('/question/down/testId/2');
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), '[LS_REQUIRED_PARAM_FAILED]');
        }
    }
}