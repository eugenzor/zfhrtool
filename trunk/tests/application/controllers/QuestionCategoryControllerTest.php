<?php
/**
 * @package zfhrtool
 * @subpackage Test
 */

/**
 * Тесты системы управления соискателями
 *
 * Тестирование контроллера отвещающего за вывод / редактирования / удаление
 * категорий вопросов теста
 * @package zfhrtool
 * @subpackage Test
 */
class QuestionCategoryControllerTest extends Zht_Test_PHPUnit_ControllerTestCase
{

    /**
     * Prepares the environment before running a applicant.
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
        $this->dispatch('/questioncategory/index/testId/1');
        $this->assertModule('default');
        $this->assertController('questioncategory');
        $this->assertAction('index');
        $this->assertResponseCode(200);
        $this->assertQueryContentContains('div#main', '<table');
    }
    
    // проверяем поведение экшена при различных входящих параметрах
    // категории отсутствуют
    public function testWithoutDatasIndexAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/questioncategory/index/testId/3');
        $this->assertModule('default');
        $this->assertController('questioncategory');
        $this->assertAction('index');
        $this->assertResponseCode(200);
                
        $this->assertQueryCount('.tablesorter', 1);
        $this->assertQueryCount('.tablesorter td', 1);
    }

    // категории присутствуют
    public function testWithDatasIndexAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/questioncategory/index/testId/2');
        $this->assertModule('default');
        $this->assertController('questioncategory');
        $this->assertAction('index');
        $this->assertResponseCode(200);
                
        $this->assertQueryCount('.tablesorter', 1);
        $this->assertQueryCountMin('.tablesorter td', 2);
    }

    // проверяем что данный экшен доступен
    public function testEditAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/questioncategory/edit');
        $this->assertModule('default');
        $this->assertController('questioncategory');
        $this->assertAction('edit');
        $this->assertResponseCode(200);
    }
    
    // проверяем генерацию формы добавления новой категории
    public function testAddingEditAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/questioncategory/edit/testId/1');
        $this->assertModule('default');
        $this->assertController('questioncategory');
        $this->assertAction('edit');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#categoryName', 1);
        $this->assertQueryCount('#categoryDescr', 1);
        $this->assertQueryCount('#testId', 1);
        $this->assertQueryCount('#categoryId', 1);
    }

    // проверяем генерацию формы редактирования существующей категории
    public function testEditingEditAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/questioncategory/edit/testId/1/categoryId/1');
        $this->assertModule('default');
        $this->assertController('questioncategory');
        $this->assertAction('edit');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#categoryName', 1);
        $this->assertQueryCount('#categoryDescr', 1);
        $this->assertQueryCount('#testId', 1);
        $this->assertQueryCount('#categoryId', 1);
    }


    // проверяем корректное добавление новой категории 
    public function testValidAddingEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'categoryName'  => 'new Category',
                'categoryDescr' => '',
                'testId'        => '1'
            ) );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/questioncategory/edit');

        $this->assertModule('default');
        $this->assertController('questioncategory');
        $this->assertAction('edit');
        $this->assertRedirect('/questioncategory/index/testId/1');
}

    // проверяем некорректное добавление новой категории 
    public function testInValidAddingEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'categoryName'  => '',
                'categoryDescr' => '123456',
                'testId'        => '1'
            ) );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/questioncategory/edit');

        $this->assertModule('default');
        $this->assertController('questioncategory');
        $this->assertAction('edit');
        $this->assertQueryCount('ul.errors', 1);
    }

    // проверяем корректное изменение категории 
    public function testValidEditingEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'categoryName'  => 'new Category',
                'categoryDescr' => '',
                'testId'        => '1',
                'categoryId'    => '5'
            ) );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/questioncategory/edit');

        $this->assertModule('default');
        $this->assertController('questioncategory');
        $this->assertAction('edit');
        $this->assertRedirect('/questioncategory/index/testId/1');
}

    // проверяем некорректное изменение категории 
    public function testInValidEditingEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'categoryName'  => '',
                'categoryDescr' => '123456',
                'testId'        => '1',
                'categoryId'    => '5'
            ) );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/questioncategory/edit');

        $this->assertModule('default');
        $this->assertController('questioncategory');
        $this->assertAction('edit');
        $this->assertQueryCount('ul.errors', 1);
    }

    // проверяем некорректное удаление 
    public function testInValidRemoveAction()
    {
        $this->setExpectedException('Exception');
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/questioncategory/remove/testId/1/categoryId/5');
        $this->assertModule('default');
        $this->assertController('questioncategory');
        $this->assertAction('remove');
        $this->assertRedirect('/questioncategory/index/testId/1');
    }

    // проверяем корректное удаление 
    public function testValidRemoveAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/questioncategory/remove/testId/1/categoryId/8');
        $this->assertModule('default');
        $this->assertController('questioncategory');
        $this->assertAction('remove');
        $this->assertRedirect('/questioncategory/index/testId/1');
    }
}