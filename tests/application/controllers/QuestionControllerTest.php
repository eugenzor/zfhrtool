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

    public function testAddingEditAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch("/question/edit/testId/10");
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
        $this->dispatch('/question/edit/testId/11/questionId/24');
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
                'testId' => '11',
                'questionId' => '24',
                'answer' => array(
                                array(
                                    'text' => '1',
                                    'flag' => '0' ),
                                array(
                                    'text' => '2',
                                    'flag' => '0' ),
                                array(
                                    'text' => '3',
                                    'flag' => '1' ) ) ) );
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/edit');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertRedirect('test/edit/testId/11');
    }

    public function testUpdateInValidEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'testId' => '11',
                'questionId' => '24',
                'answer' => array(
                                array(
                                    'text' => '1',
                                    'flag' => '0' ),
                                array(
                                    'text' => '2',
                                    'flag' => '0' ),
                                array(
                                    'text' => '3',
                                    'flag' => '1' ) ) ) );
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
                'questionText' => 'вопрос',
                'testId' => '11',
                'answer' => array(
                                array(
                                    'text' => '1',
                                    'flag' => '0' ),
                                array(
                                    'text' => '2',
                                    'flag' => '0' ),
                                array(
                                    'text' => '3',
                                    'flag' => '1' ) ) ) );
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/edit');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertRedirect('test/edit/testId/11');
    }

    public function testRemoveAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/remove/testId/11/questionId/24');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertRedirect('test/edit/testId/11');
    }

    public function testWithoutTestIdRemoveAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/remove/questionId/25');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertRedirect('test/');
    }

    public function testUpAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/up/testId/5/questionId/22');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertAction('up');
        $this->assertRedirect('test/edit/testId/5');
    }

    public function testWithoutTestIdUpAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/up/questionId/22');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertAction('up');
        $this->assertRedirect('test/');
    }

    public function testWithoutQuestionIdUpAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        try {
            $this->dispatch('/question/up/testId/5');
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), '[LS_REQUIRED_PARAM_FAILED]');
        }
    }

    public function testDownAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/down/testId/5/questionId/22');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertAction('down');
        $this->assertRedirect('test/edit/testId/5');
    }

    public function testWithoutTestIdDownAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/question/down/questionId/22');
        $this->assertModule('default');
        $this->assertController('question');
        $this->assertAction('down');
        $this->assertRedirect('test/');
    }

    public function testWithoutQuestionIdDownAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        try {
            $this->dispatch('/question/down/testId/5');
        } catch (Exception $e) {
            $this->assertEquals($e->getMessage(), '[LS_REQUIRED_PARAM_FAILED]');
        }
    }
}