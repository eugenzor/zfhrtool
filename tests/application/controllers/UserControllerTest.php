<?php
/**
 * @package zfhrtool
 * @subpackage Test
 */

require_once dirname(__FILE__) . '/MockZend_Mail.php';

/**
 * Тесты интерфейса авторизации
 *
 * Тестирование контроллера отвещающего за авторизацию, регистрацию пользователей и напоминаие паролей
 * @package zfhrtool
 * @subpackage Test
 */
class UserControllerTest extends Zht_Test_PHPUnit_ControllerTestCase
{

    /**
     * Prepares the environment before running a test.
     */

    protected function setUp() {
        $this->setDbDump(dirname(__FILE__) . '/_files/setup.sql');
        parent::setUp ();
    }

    protected function _doLogin( $email, $password )
    {
        $auth = Auth::getInstance ();
        $result = $auth ->
            authenticate ( new Auth_Adapter ( $email, $password ) );
    }

    public function testSignup()
    {
        $this->dispatch('/user/signup');
        $this->assertController('user');
        $this->assertAction('signup');
        $this->assertQueryCountMin('form', 1);
        $this->assertQueryCount('input#captcha-id', 1);
    }

    private function _invalidSignup($data)
    {
        $_SESSION = array(
            "Zend_Form_Captcha_51adf32ef0e18d255ad793df28e1e6f3" => array(
                "word" => "q9j9g2"
            )
          );

        $this->_request->setMethod('post')->setPost($data);
        $this->dispatch('/user/signup');

        $this->assertController('user');
        $this->assertAction('signup');
        $this->assertQueryCount('div.message', 0);
        $this->assertQueryCount('div.error', 1);
    }

    public function testSignupInvalidNickname()
    {

        $this->_invalidSignup(array(
            'nickname' => '',
            'email'=>'testname@test.com',
            'password'=>123456,
            'captcha'=>array(
                'input'=>"q9j9g2",
                'id'=>"51adf32ef0e18d255ad793df28e1e6f3"
            )
        ));
        $this->assertQueryCount('#nickname-element .errors li', 1);
    }

    public function testSignupInvalidEmail()
    {
        $this->_invalidSignup(array(
            'nickname' => 'testname',
            'email'=>'',
            'password'=>'123456',
            'captcha'=>array(
                'input'=>"q9j9g2",
                'id'=>"51adf32ef0e18d255ad793df28e1e6f3"
            )
        ));
        $this->assertQueryCount('#email-element .errors li', 1);
    }

    public function testSignupInvalidPassword()
    {
        $this->_invalidSignup(array(
            'nickname' => 'testname',
            'email'=>'testname@test.com',
            'password'=>'',
            'captcha'=>array(
                'input'=>"q9j9g2",
                'id'=>"51adf32ef0e18d255ad793df28e1e6f3"
            )
        ));
        $this->assertQueryCount('#password-element .errors li', 1);
    }

    public function testSignupInvalidCaptchaId()
    {
        $this->_invalidSignup(array(
                'nickname' => 'testname',
                'email'=>'testname@test.com',
                'password'=>'123456',
                'captcha'=>array(
                    'input'=>"q9j9g2",
                    'id'=>'invalidcaptchaid'
                )
        ));
//echo $this->_response->getBody();
    }

    public function testSignupInvalidCaptchaWord()
    {
        $this->_invalidSignup(array(
                'nickname' => 'testname',
                'email'=>'testname@test.com',
                'password'=>'123456',
                'captcha'=>array(
                    'input'=>"invalid",
                    'id'=>'51adf32ef0e18d255ad793df28e1e6f3'
                )
        ));
    }



    public function testValidSignup()
    {
        $_SESSION = array(
            "Zend_Form_Element_Hash_unique_csrf" => array("hash" => "3dcf352578a7ec4862156f189f74efa8"),
            "Zend_Form_Captcha_51adf32ef0e18d255ad793df28e1e6f3" => array(
                "word" => "q9j9g2"
            )
          );

        $this->_request->setMethod('post')
            ->setPost(array(
                'nickname' => 'testname',
                'email'=>'testname@test.com',
                'password'=>'123456',
                'captcha'=>array(
                    'input'=>"q9j9g2",
                    'id'=>"51adf32ef0e18d255ad793df28e1e6f3"
                )
            ));
        $this->dispatch('/user/signup');
        $this->assertController('user');
        $this->assertAction('signup');
        $this->assertQueryCount('div.message', 1);
        $this->assertQueryCount('div.error', 0);

        $this->assertContains('testname', Zend_Mail::$bodyHtml);
        $this->assertContains('123456', Zend_Mail::$bodyHtml);
        $this->assertContains(
            md5 ( 'activate' . Auth_Adapter::getEncodedPassword('testname@test.com', '123456') . 'account' )
            ,Zend_Mail::$bodyHtml
        );

        $this->assertContains('testname', Zend_Mail::$bodyText);
        $this->assertContains('123456', Zend_Mail::$bodyText);
        $this->assertContains(
            md5 ( 'activate' . Auth_Adapter::getEncodedPassword('testname@test.com', '123456') . 'account' )
            ,Zend_Mail::$bodyText
        );
    }

    public function testActivateInvalidId()
    {
        $this->dispatch('/user/activate/id/12345/code/e7d55174ad4a488c0884be6e8a5faaaa');
        $this->assertController('user');
        $this->assertAction('activate');
        $this->assertQueryCount('div.error', 1);
    }

    public function testActivateInvalidCode()
    {
        $this->dispatch('/user/activate/id/34/code/e7d55174ad4a488c0884be6e8a5faaaa');
        $this->assertController('user');
        $this->assertAction('activate');
        $this->assertQueryCount('div.error', 1);
    }

    public function testActivateValid()
    {
        $this->dispatch('/user/activate/id/1/code/a9d93fb86ed0007746c0e2b85661fa07');
        $this->assertController('user');
        $this->assertAction('activate');
        $this->assertQueryCount('div.message', 1);

        $db = Zend_Db_Table_Abstract::getDefaultAdapter ();
        $this->assertEquals($db->fetchOne("SELECT `status` FROM users WHERE id=1"), 'active');
        $this->assertContains('Иванов Иван Иванович', Zend_Mail::$bodyHtml);
        $this->assertContains('Иванов Иван Иванович', Zend_Mail::$bodyText);

    }






    public function testSigninForm()
    {
        $this->dispatch('/user/signin');
        $this->assertController('user');
        $this->assertAction('signin');
        $this->assertQueryCountMin("form", 1);
        $this->assertQueryCount('input#email', 1);
        $this->assertQueryCount('input#password', 1);
        $this->assertQueryCount('input#csrf', 1);
    }

    private function _invalidSignin($data)
    {
        $_SESSION = array("Zend_Form_Element_Hash_unique_csrf" => array(
            "hash" => "14d2e0a530c70ae573bdb645d54a9db4"
        ));
        Auth_Adapter::$sleepTime = 0;

        $this->_request->setMethod('post')->setPost($data);
        $this->dispatch('/user/signin');
        $this->assertController('user');
        $this->assertAction('signin');
        $this->assertQueryCount('div.error', 1);
    }

    public function testSugninInvalidEmail()
    {
        $this->_invalidSignin(array(
            'email'=>'invalid@mail.com',
            'password'=>'123456',
            'csrf'=>'14d2e0a530c70ae573bdb645d54a9db4'));
    }

    public function testSigninInvalidPassword()
    {
        $this->_invalidSignin(array(
            'email'=>'fedor@zfhrtool.net',
            'password'=>'invalidpass',
            'csrf'=>'14d2e0a530c70ae573bdb645d54a9db4'));
    }


    public function testValidSignin()
    {
        $_SESSION = array("Zend_Form_Element_Hash_unique_csrf" => array(
            "hash" => "14d2e0a530c70ae573bdb645d54a9db4"
        ));

        $this->_request->setMethod('post')->setPost(array(
            'email'=>'fedor@zfhrtool.net',
            'password'=>'111111',
            'csrf'=>'14d2e0a530c70ae573bdb645d54a9db4'));
        $this->dispatch('/user/signin');
        $this->assertController('user');
        $this->assertAction('signin');
        $this->assertRedirect('/editor');
    }








    public function testSignout()
    {
        $_SESSION = array("Zend_Auth" => array("storage" => "2"));
        $this->dispatch('/user/signout');
        $this->assertController('user');
        $this->assertAction('signout');
        $this->assertRedirectTo('/');
        $this->assertTrue(empty($_SESSION['Zend_Auth']));
    }








    public function testForgetPassword()
    {
         $this->dispatch('/user/forget-password');
         $this->assertController('user');
         $this->assertAction('forget-password');
         $this->assertQueryCountMin('form', 1);
         $this->assertQueryCount('input[name="email"]', 1);
         $this->assertQueryCount('input[name="captcha[id]"]', 1);
         $this->assertQueryCount('input[name="captcha[input]"]', 1);
         $this->assertQueryCount('input[name="csrf"]', 1);
    }

    private function _invalidForget($data)
    {
        $_SESSION = array(
            "Zend_Form_Captcha_1278c2ef0559f6b7c1e3ed964254b407" => array(
              "word" => "ze4yr4"
            ),
            "Zend_Form_Element_Hash_unique_csrf" => array(
              "hash" => "b665bd8a503e5268dcafed5a5f689450"
            )
        );
        $this->getRequest()->setMethod('post')->setPost($data);
         $this->dispatch('/user/forget-password');
         $this->assertController('user');
         $this->assertAction('forget-password');
         $this->assertQueryCount('div.error', 1);
    }

    public function testForgetInvalidEmail()
    {
        $this->_invalidForget(array(
            'email'=>'invalid@mail.com',
            'csrf'=>'b665bd8a503e5268dcafed5a5f689450',
            'captcha'=>array(
                'id' => '1278c2ef0559f6b7c1e3ed964254b407',
                'input' => 'ze4yr4'
            )
        ));
        $this->assertQueryCount('dl.zend_form .errors', 0);
    }

    public function testForgetInvalidCsrf()
    {
        $this->_invalidForget(array(
            'email'=>'fedor@zfhrtool.net',
            'csrf'=>'invalid',
            'captcha'=>array(
                'id' => '1278c2ef0559f6b7c1e3ed964254b407',
                'input' => 'ze4yr4'
            )
        ));
//        echo $this->_response->getBody();
        $this->assertQueryCount('dl.zend_form .errors', 1);
    }

    public function testForgetInvalidCaptchaId()
    {
        $this->_invalidForget(array(
            'email'=>'fedor@zfhrtool.net',
            'csrf'=>'b665bd8a503e5268dcafed5a5f689450',
            'captcha'=>array(
                'id' => 'invalid',
                'input' => 'ze4yr4'
            )
        ));
        $this->assertQueryCount('dl.zend_form .errors', 1);
    }

    public function testForgetInvalidCaptchaWord()
    {
        $this->_invalidForget(array(
            'email'=>'fedor@zfhrtool.net',
            'csrf'=>'b665bd8a503e5268dcafed5a5f689450',
            'captcha'=>array(
                'id' => '1278c2ef0559f6b7c1e3ed964254b407',
                'input' => 'invalid'
            )
        ));
        $this->assertQueryCount('dl.zend_form .errors', 1);
    }

    public function testValidForget()
    {
        $_SESSION = array(
            "Zend_Form_Captcha_1278c2ef0559f6b7c1e3ed964254b407" => array(
              "word" => "ze4yr4"
            ),
            "Zend_Form_Element_Hash_unique_csrf" => array(
              "hash" => "b665bd8a503e5268dcafed5a5f689450"
            )
        );
        $this->getRequest()->setMethod('post')->setPost(array(
            'email'=>'fedor@zfhrtool.net',
            'csrf'=>'b665bd8a503e5268dcafed5a5f689450',
            'captcha'=>array(
                'id' => '1278c2ef0559f6b7c1e3ed964254b407',
                'input' => 'ze4yr4'
            )
        ));
         $this->dispatch('/user/forget-password');

         $this->assertController('user');
         $this->assertAction('forget-password');
         $this->assertQueryCount('div.error', 0);
         $this->assertQueryCount('div.message', 1);

        $this->assertContains('Федоров Федор Федорович', Zend_Mail::$bodyHtml);
        $this->assertContains(
            md5 ( 'recover' . Auth_Adapter::getEncodedPassword('fedor@zfhrtool.net', '111111') . 'password' )
            ,Zend_Mail::$bodyHtml
        );

        $this->assertContains('Федоров Федор Федорович', Zend_Mail::$bodyText);
        $this->assertContains(
            md5 ( 'recover' . Auth_Adapter::getEncodedPassword('fedor@zfhrtool.net', '111111') . 'password' )
            ,Zend_Mail::$bodyText
        );
    }









    public function testRecoverPasswordWithoutParams()
    {
        $this->dispatch('/user/recover-password');
        $this->assertQueryCount('div.error', 1);
    }

    public function testRecoverPasswordInvalidId()
    {
        $this->dispatch('/user/recover-password/id/12345/code/1234');
        $this->assertQueryCount('div.error', 1);
    }

    public function testRecoverPasswordValidCode()
    {
        $this->dispatch('/user/recover-password/id/2/code/' . md5 ( 'recover1618fe490d041584a583457fd3f7627fpassword' ));
        $this->assertQueryCount('div.error', 0);
        $this->assertQueryCountMin('form', 1);
        $this->assertQueryCount('input[name="password"]', 1);
        $this->assertQueryCount('input[name="captcha[id]"]', 1);
        $this->assertQueryCount('input[name="captcha[input]"]', 1);
        $this->assertQueryCount('input[name="csrf"]', 1);
    }

    private function _invalidRecover($data)
    {
        $_SESSION = array(
            "Zend_Form_Captcha_6c8fbaf4ac101368309023f8c3556bc9" => array(
              "word" => "86r7mu"
            ),
            "Zend_Form_Element_Hash_unique_csrf" => array("hash" => "2ec8e2568c6b762ef7c96541f3e6c19a")
        );
        $this->getRequest()->setMethod('post')->setPost($data);
         $this->dispatch('/user/recover-password/id/2/code/f2d28ff2f43141a0f399e84089e60544');
         $this->assertController('user');
         $this->assertAction('recover-password');
         $this->assertQueryCount('div.error', 1);
    }

    public function testRecoverInvalidPassword()
    {
        $_SESSION = array(
            "Zend_Form_Captcha_6c8fbaf4ac101368309023f8c3556bc9" => array(
              "word" => "86r7mu"
            ),
            "Zend_Form_Element_Hash_unique_csrf" => array("hash" => "2ec8e2568c6b762ef7c96541f3e6c19a")
        );
        $this->getRequest()->setMethod('post')->setPost(array(
            'password' => '',
            'csrf' => '2ec8e2568c6b762ef7c96541f3e6c19a',
            'captcha' => array(
                'id' => '6c8fbaf4ac101368309023f8c3556bc9',
                'input' => '86r7mu'
            )
        ));
        $this->dispatch('/user/recover-password/id/2/code/7650de183158be393a7ada9ff6f400c1');
         $this->assertController('user');
         $this->assertAction('recover-password');
         $this->assertQueryCount('div.error', 1);
    }

    public function testValidRecover()
    {
        $_SESSION = array(
            "Zend_Form_Captcha_6c8fbaf4ac101368309023f8c3556bc9" => array(
              "word" => "86r7mu"
            ),
            "Zend_Form_Element_Hash_unique_csrf" => array("hash" => "2ec8e2568c6b762ef7c96541f3e6c19a")
        );
        $this->getRequest()->setMethod('post')->setPost(array(
            'password' => 'qwerty',
            'csrf' => '2ec8e2568c6b762ef7c96541f3e6c19a',
            'captcha' => array(
                'id' => '6c8fbaf4ac101368309023f8c3556bc9',
                'input' => '86r7mu'
            )
        ));
        $this->dispatch('/user/recover-password/id/2/code/' . md5 ( 'recover1618fe490d041584a583457fd3f7627fpassword' ));
         $this->assertController('user');
         $this->assertAction('recover-password');
         $this->assertQueryCount('div.error', 0);
         $this->assertQueryCount('div.message', 1);

         $db = Zend_Db_Table_Abstract::getDefaultAdapter ();
         $this->assertEquals(
             Auth_Adapter::getEncodedPassword('fedor@zfhrtool.net', 'qwerty'),
             $db->fetchOne("SELECT password FROM users WHERE id = 2"));
    }
    
    public function testIndexAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/user');
        $this->assertModule('default');
        $this->assertController('user');
        $this->assertAction('index');
        $this->assertResponseCode(200);
        $this->assertQueryContentContains('div#main', '<table');
    }

    public function testShowEditAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/user/edit/Id/1');
        $this->assertModule('default');
        $this->assertController('user');
        $this->assertAction('edit');
        $this->assertResponseCode(200);
        $this->assertQueryCount('#userId', 1);
        $this->assertQueryCount('#Email', 1);
        $this->assertQueryCount('#Nickname', 1);
        $this->assertQueryCount('#Role', 1);
    }

    public function testIncorrectSubmitEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'userId' => '1',
                'Email' => '--!@Nickname@!--', // incorrect
                'Nickname' => '--!@Nickname@!--',
                'Role' => 'staff'
            )
        );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/user/edit/Id/1');
        $this->assertModule('default');
        $this->assertController('user');
        $this->assertAction('edit');
        $this->assertQueryCount('ul.errors', 1);
        $this->assertQueryContentContains('ul.errors', '<li>\'!--');
    }

    public function testCorrectSubmitEditAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'userId' => '1',
                'Email' => 'test_mail@gmail.com',
                'Nickname' => '--!@Nickname@!--',
                'Role' => 'staff'
            )
        );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/user/edit/Id/1');
        $this->assertModule('default');
        $this->assertController('user');
        $this->assertAction('edit');
        $this->assertRedirect('/user');
    }

    public function testShowRoleAction()
    {
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/user/role/Id/1');
        $this->assertModule('default');
        $this->assertController('user');
        $this->assertAction('role');
        $this->assertResponseCode(200);
        $this->assertQueryCount('#userId', 1);
        $this->assertQueryCount('#Role', 1);
    }

    public function testIncorrectSubmitRoleAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'userId' => '',
                'Role' => 'lkjsdlkj'
            )
        );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/user/role/Id/1');
        $this->assertModule('default');
        $this->assertController('user');
        $this->assertAction('role');
        $this->assertQueryCount('ul.errors', 1);
        $this->assertQueryContentContains('ul.errors', '<li>\'lkjsdlkj');
    }

    public function testCorrectSubmitRoleAction()
    {
        $this -> _request -> setMethod( 'post' ) -> setPost(
            array(
                'userId' => '1',
                'Role' => 'staff'
            )
        );
        $this ->_doLogin('ostapiuk@gmail.com', '654321');
        $this->dispatch('/user/role/Id/1');
        $this->assertModule('default');
        $this->assertController('user');
        $this->assertAction('role');
        $this->assertRedirect('/user');
    }


}