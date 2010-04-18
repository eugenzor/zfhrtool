<?php
/**
 * @package zfhrtool
 * @subpackage Test
 */

/**
 * Тесты системы управления соискателями
 *
 * Тестирование контроллера отвещающего за вывод / редактирования / удалние соискателей
 * @package zfhrtool
 * @subpackage Test
 */
class ApplicantControllerTest extends Zht_Test_PHPUnit_ControllerTestCase
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
        $this->dispatch('/applicant');
        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('index');

        $this->assertResponseCode(200);
        $this->assertQueryContentContains('div#main', '<table');
    }

    // проверяем поведение экшена по умолчанию
    public function testDefaultIndexAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant');
        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('index');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#vacancyId', 1);
        $this->assertQueryCount('#status', 1);
        $this->assertQueryCount('.tablesorter', 1);
    }
/*
    // проверяем поведение экшена при входящих параметрах
    public function testWithInputDataIndexAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
            )
        );
        $this->dispatch('/applicant');
        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('index');
        $this->assertResponseCode(200);
        $this->assertQueryContentContains('div#main', '<table');
    }
*/     
    // проверяем что данный экшен доступен
    public function testAddAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant/add');
        
        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('add');
        $this->assertResponseCode(200);
    }

    public function testAddingAddAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant/add');
        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('add');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#LastName', 1);
        $this->assertQueryCount('#Name', 1);
        $this->assertQueryCount('#Patronymic', 1);
        $this->assertQueryCount('#Email', 1);
        $this->assertQueryCount('#Birth', 1);
        $this->assertQueryCount('#VacancyId', 1);
        $this->assertQueryCount('#Phone', 1);
        $this->assertQueryCount('#Resume', 1);
        $this->assertQueryCount('#applicantId', 1);
    }

    public function testAddingEditAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant/edit/applicantId/1');
        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('edit');
        $this->assertResponseCode(200);

        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#LastName', 1);
        $this->assertQueryCount('#Name', 1);
        $this->assertQueryCount('#Patronymic', 1);
        $this->assertQueryCount('#Email', 1);
        $this->assertQueryCount('#Birth', 1);
        $this->assertQueryCount('#VacancyId', 1);
        $this->assertQueryCount('#Phone', 1);
        $this->assertQueryCount('#Resume', 1);
        $this->assertQueryCount('#applicantId', 1);
    }

/*
 // isValid ??
    public function testUpdateValidAddAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'LastName' => 'LastName',
                'Name' => 'Name',
                'Patronymic' => 'Patronymic',
                'Birth' => '1987-10-10',
                'VacancyId' => '1',
                'Email' => 'ihor@ukr.net',
                'Phone' => '0987654321',
                'Resume' => 'Resume'
            ) );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant/add');

        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('add');
        $this->assertRedirect('/applicant');
        $this->assertQueryCount('#LastName', 1);
}

    public function testUpdateValidEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'LastName' => 'Surname',
                'Name' => 'Name',
                'Patronymic' => 'Patronymic',
                'Email' => 'email@host.com',
                'Birth' => '1987-10-11',
                'VacancyId' => '1',
                'Phone' => '0971234561',
                'Resume' => 'Resume',
                'applicantId' => '16'
            ) );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant/edit');
        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('edit');
        $this->assertRedirect('/applicant');
    }
*/
    public function testUpdateInValidEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'Name' => 'Name',
                'Patronymic' => 'Patronymic',
                'Email' => 'email@host.com',
                'Birth' => '1987-11-10',
                'VacancyId' => '1',
                'Phone' => '0971234561',
                'Resume' => 'Resume',
                'applicantId' => '16'
            )
        );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant/edit/applicantId/16');
        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('edit');
        $this->assertQueryCount('ul.errors', 1);
        $this->assertQueryContentContains('ul.errors', '<li>Value');
    }

    public function testAddingComment()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'Comment' => '--@!TestComment!@--',
                'applicantId' => '16'
            )
        );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant/show/applicantId/16');
        
        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('show');
        $this->assertRedirect('/applicant/show/applicantId/16');
    }

    public function testShowAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant/show/applicantId/16');
        
        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('show');
        $this->assertQueryContentContains('div#main', '--@!TestComment!@--');
    }
    
    public function testStatusAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant/status/applicantId/16');

        $this->assertResponseCode(200);
        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('status');
        $this->assertQueryCount('form', 1);
        $this->assertQueryCount('#Status', 1);
        $this->assertQueryCount('#Comment', 1);
    }
    
    public function testStatusChangedIncorrect()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'status' => 'new',
                'Comment' => '',
                'applicantId' => '16'
            )
        );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant/status/applicantId/16');

        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('status');
        $this->assertQueryCount('ul.errors', 1);
        $this->assertQueryContentContains('ul.errors', '<li>Value is');
    }

    public function testStatusChanged()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'status' => 'new',
                'Comment' => '--@!TestComment!@--',
                'applicantId' => '16'
            )
        );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant/status/applicantId/16');

        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('status');
        $this->assertRedirect('/applicant');
    }

    public function testRemoveAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/applicant/remove/applicantId/16');
        $this->assertModule('default');
        $this->assertController('applicant');
        $this->assertAction('remove');
        $this->assertRedirect('/applicant');
    }
}