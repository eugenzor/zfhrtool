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
}