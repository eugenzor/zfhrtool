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
class CategoryControllerTest extends Zht_Test_PHPUnit_ControllerTestCase
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
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/category');
        $this->assertModule('default');
        $this->assertController('category');
        $this->assertAction('index');
        $this->assertQueryContentContains('h1.title', 'Категории ');
    }

    public function testAddingEditAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/category/edit');
        $this->assertModule('default');
        $this->assertController('category');
        $this->assertAction('edit');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#categoryName', 1);
        $this->assertQueryCount('#categoryDescr', 1);
    }

    public function testEditingEditAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/category/edit/categoryId/1');
        $this->assertModule('default');
        $this->assertController('category');
        $this->assertAction('edit');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#categoryName', 1);
        $this->assertQueryCount('#categoryDescr', 1);
        $this->assertQueryCount('#categoryId', 1);
    }

    public function testUpdateValidEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'categoryName' => 'Common',
                'categoryId' => '1',
                'categoryDescr' => 'Коммент1' ) );
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/category/edit');
        $this->assertModule('default');
        $this->assertController('category');
        $this->assertAction('index');
    }

    public function testUpdateInValidEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'categoryId' => '1',
                'categoryDescr' => 'Коммент1' ) );
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/category/edit');
        $this->assertModule('default');
        $this->assertController('category');
        $this->assertAction('edit');
        $this->assertQueryCount('dd#categoryName-element ul.errors', 1);
    }

    public function testUpdateValidWithousCategoryIdEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'categoryName' => 'Common',
                'categoryDescr' => 'Коммент1' ) );
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/category/edit');
        $this->assertModule('default');
        $this->assertController('category');
        $this->assertAction('index');
    }

    // проверяем что данный экшен доступен
    public function testRemoveAction()
    {
        $this ->_doLogin('meestro@ukr.net', '123456');
        $this->dispatch('/category/remove/categoryId/15');
        $this->assertModule('default');
        $this->assertController('category');
        $this->assertAction('index');
    }
}