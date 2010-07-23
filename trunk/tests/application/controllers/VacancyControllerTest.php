<?php
/**
 * @package zfhrtool
 * @subpackage Test
 */

/**
 * Тесты системы управления вакансиями
 *
 * Тестирование контроллера отвещающего за вывод / редактирования / удалние вакансий
 * @package zfhrtool
 * @subpackage Test
 */
class VacancyControllerTest extends Zht_Test_PHPUnit_ControllerTestCase
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
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/vacancy');
        $this->assertModule('default');
        $this->assertController('vacancy');
        $this->assertAction('index');
        $this->assertQueryContentContains('div#main', '<table');
    }

    public function testAddingEditAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/vacancy/edit');
        $this->assertModule('default');
        $this->assertController('vacancy');
        $this->assertAction('edit');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#Name', 1);
        $this->assertQueryCount('#Num', 1);
        $this->assertQueryCount('#Duties', 1);
        $this->assertQueryCount('#Requirements', 1);
        $this->assertQueryCount('#test_1', 1);
        $this->assertQueryCount('#test_2', 1);
        $this->assertQueryCount('#test_3', 1);
    }

    public function testEditingEditAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/vacancy/edit/vacancyId/1');
        $this->assertModule('default');
        $this->assertController('vacancy');
        $this->assertAction('edit');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#Name', 1);
        $this->assertQueryCount('#Num', 1);
        $this->assertQueryCount('#Duties', 1);
        $this->assertQueryCount('#Requirements', 1);
        $this->assertQueryCount('#vacancyId', 1);
        $this->assertQueryCount('#test_1', 1);
        $this->assertQueryCount('#test_2', 1);
        $this->assertQueryCount('#test_3', 1);
    }

    public function testUpdateValidEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'Name' => 'Проверка',
                'vacancyId' => '1',
                'Num' => '2',
                'Duties' => 'Обязанности',
                'Requirements' => 'Требования',
            ) );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/vacancy/edit');
        $this->assertModule('default');
        $this->assertController('vacancy');
        $this->assertAction('edit');
        $this->assertRedirect('/applicant');
    }

    public function testUpdateInValidEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'vacancyId' => '1',
                'Num' => '2',
                'Duties' => 'Обязанности',
                'Requirements' => 'Требования'
            )
        );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/vacancy/edit');
        $this->assertModule('default');
        $this->assertController('vacancy');
        $this->assertAction('edit');
        $this->assertQueryCount('dd#Name-element ul.errors', 1);
    }

    // проверяем что данный экшен доступен
    public function testRemoveAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/vacancy/remove/vacancyId/3');
        $this->assertModule('default');
        $this->assertController('vacancy');
        $this->assertAction('remove');
        $this->assertRedirect('/vacancy');
    }

    // проверяем что данный экшен доступен
    public function testInvalidRemoveAction()
    {
        try {
            $this ->_doLogin('ostapiuk@gmail.com', '654321');
            $this->dispatch('/vacancy/remove/vacancyId/1');
        } catch (Exception $ex) {
            $this->assertModule('default');
            $this->assertController('vacancy');
            $this->assertAction('remove');
            $this->assertNotRedirect();
        }
    }

}