<?php
/**
 * @package zfhrtool
 * @subpackage Controller
 */


/**
 * Контроллер пользователей
 *
 * Обеспечивает авторизацию, регистрацию, активацию, напоминание паролей и прочие пользовательские операции
 * @package zfhrtool
 * @subpackage Controller
 */
class UserController extends Controller_Action_Abstract
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
     * Список пользователей
     *
     * возвращает список всех пользователей системы
     * @return void
     */
    public function indexAction()
    {
        if ($this -> _authorize('users', 'view')) {
            $Users = new Users();
            $this -> view -> users = $Users -> fetchAll();
        }
    }

    /**
     * Изменение роли
     *
     * изменяет роль пользователя
     * @return void
     */
    public function roleAction()
    {
        if ($this -> _authorize('users', 'change_role')) {
            $userId = $this -> getRequest() -> getParam('Id');
            $form = new Form_User_Role();
            $form -> setAction ( $this->view->url ( array (
                'controller' => 'user',
                'action' => 'role'
            ) ) );
            $Users = new Users();
            if ($this -> getRequest() -> isPost()) {
                if ($form -> isValid($_POST)) {
                    $user = $Users -> getObjectById( $form -> userId -> getValue() );
                    if ($user instanceof User) {
                        $user -> role = $form -> Role -> getValue();
                        $user -> save();
                        $this -> _helper -> redirector ( 'index', 'user' );
                    }
                }
                else  {
                    $form -> populate( $this -> getRequest() -> getParams() );
                    $this -> view -> form = $form;
                }
            }
            elseif ($userId > 0) {
                $user = $Users -> getObjectById($userId);
                if ($user) {
                    $form -> populate ( array(
                        'userId' => $user -> id,
                        'Role' => $user -> role
                    ));
                    $this -> view -> form = $form;
                }
            }
        }
    }


    /**
     * Редактирование пользователя
     *
     * изменяет ник, почту, роль
     * @return void
     */
    public function editAction()
    {
        if ($this -> _authorize('users', 'edit')) {
            $userId = $this -> getRequest() -> getParam('Id');
            $form = new Form_User_Edit();
            $Users = new Users();
            if ($this -> getRequest() -> isPost()) {
                if ($form -> isValid($_POST)) {
                    $user = $Users -> getObjectById( $form -> userId -> getValue() );
                    if ($user) {
                        $user -> role = $form -> Role -> getValue();
                        $user -> nickname = $form -> Nickname -> getValue();
                        $user -> email = $form -> Email -> getValue();
                        $user -> save();
                        $this -> _helper -> redirector ( 'index', 'user' );
                    }
                }
                else {
                    $form -> populate($this->getRequest()->getParams());
                    $this -> view -> form = $form;
                }
            }
            elseif ($userId > 0) {
                $user = $Users -> getObjectById($userId);
                if ($user) {
                    $form -> populate ( array(
                        'userId' => $user -> id,
                        'Role' => $user -> role,
                        'Nickname' => $user -> nickname,
                        'Email' => $user -> email
                    ));
                    $this -> view -> form = $form;
                }
            }
        }
    }

    /**
     * Удаление пользователя
     * @return void
     */
    public function removeAction()
    {
        if ( $this -> _authorize( 'users', 'remove')) {
            $objUsers = new Users ();

            $arrParams = $this->getRequest()->getParams();

            if (array_key_exists('userId', $arrParams) &&
                    !empty($arrParams['userId'])) {
                //$objUsers -> removeUserById($arrParams['userId']);
            }

            $this->_forward ( 'index', 'users' );
        }
    }



    /**
     * Регистрация пользователя
     *
     * Пользователи должны сами себя регистрировать в системе
     * @return void
     */
    public function signupAction()
    {
        Auth::getInstance ()->clearIdentity ();

        $form = new Form_User_Signup();

        if ($this->getRequest ()->isPost ()){
            try {
                if ($form->isValid ( $_POST ) === false) {
                    throw new Exception ( '[LS_VALIDATTION_FORM_FAILED]' );
                }

                $users = new Users ( );
                $user = $users->getUserByEmail ( $form->email->getValue () );
                if ($user instanceof User) {
                    throw new Exception ( '[LS_AUTH_USEREMAIL_ALREADY_EXISTS]' );
                }
                $user = $users->getUserByNickname ( $form->nickname->getValue () );
                if ($user instanceof User) {
                    throw new Exception ( '[LS_AUTH_USERNICKNAME_ALREADY_EXISTS]' );
                }

                // signup user
                $user = $users->createRow ();
                $user->setNickname ( $form->nickname->getValue () );
                $user->setEmail ( $form->email->getValue () );
                $user->setPassword ( Auth_Adapter::getEncodedPassword ( $form->email->getValue (), $form->password->getValue () ) );
                $user->setStatus ( User::STATUS_VERIFYING );
                $user->setRole ( 'guest' );
                $user->setLastLoginAt ();
                $user->save ();

                // send email
                $config = Zend_Registry::getInstance ()->get ( 'config' );
                $mail = new Zend_Mail ( 'UTF-8' );
                $mail->addTo ( $user->getEmail () );
                $mail->setFrom ( $config->administration->email, $config->administration->title );

                $mail->setSubject ( 'Активация учетной записи' );
                $viewMail = new Zht_View_Email ( );
                $viewMail->nickname = $user->getNickname ();
                $viewMail->email = $user->getEmail ();
                $viewMail->password = $form->password->getValue ();
                $viewMail->id = $user->getId ();
                $viewMail->code = md5 ( 'activate' . $user->getPassword () . 'account' );
                $viewMail->host = $this->_request->getHttpHost ();
                $mail->setBodyText ( $viewMail->render ( 'user/activate-email-text.phtml' ) );
                $mail->setBodyHtml ( $viewMail->render ( 'user/activate-email-html.phtml' ) );
                $mail->send ();

                return $this->render ( 'signup-success' );

            } catch ( Exception $e ) {
                $form->addErrorMessage ( $this->view->translate ( $e->getMessage () ) );
            }
        }
        $this->view->form = $form;
    }

    /**
     * Активация пользователя
     *
     * После того как пользователь зарегистрировался, он должен активировать свою
     * учетную запись переходом по ссылке, которая приходит на почту
     * @return void
     */
    public function activateAction()
    {
        Auth::getInstance ()->clearIdentity ();

        $userId = $this->_getParam ( 'id', false );
        $userCheckCode = $this->_getParam ( 'code', false );
        try {
            if (($userId === false) || ($userCheckCode === false)) {
                throw new Exception ( 'No verification data' );
            }
            $users = new Users ( );
            $user = $users->getObjectById ( $userId );
            /* @var $user User */
            if ($user === false) {
                throw new Exception ( 'No user with id ' . $userId );
            }
            if ($user->getStatus () !== User::STATUS_VERIFYING) {
                throw new Exception ( 'You cant change your status' );
            }

            $checkCode = md5 ( 'activate' . $user->getPassword () . 'account' );
            if ($userCheckCode !== $checkCode) {
                throw new Exception ( 'Wrong checkcode' );
            }

            // Activate
            $user->setStatus ( User::STATUS_ACTIVE );
            $user->save ();

            $this->view->activationFailed = false;

            // send welcome email
            $config = Zend_Registry::getInstance ()->get ( 'config' );
            $mail = new Zend_Mail ( 'UTF-8' );
            $mail->addTo ( $user->getEmail () );
            $mail->setFrom ( $config->administration->email, $config->administration->title );

            $mail->setSubject ( 'Добро пожаловать!' );
            $viewMail = new Zht_View_Email ( );
            $viewMail->nickname = $user->getNickname ();
            $mail->setBodyText ( $viewMail->render ( 'user/welcome-email-text.phtml' ) );
            $mail->setBodyHtml ( $viewMail->render ( 'user/welcome-email-html.phtml' ) );
            $mail->send ();

        } catch ( Exception $e ) {
            $this->view->activationFailed = true;
        }
    }

    /**
     * Авторизация пользователя
     * @return void
     */
    public function signinAction()
    {
        Auth::getInstance ()->clearIdentity ();

        $form = new Form_User_Signin();

        if ($this->getRequest ()->isPost ()){
            try {
                if ($form->isValid ( $_POST ) === false) {
                    throw new Exception ( '[LS_AUTH_SIGNIN_ERROR]' );
                }

                $email = $form->email->getValue ();
                $password = $form->password->getValue ();

                $auth = Auth::getInstance ();

                $result = $auth->authenticate ( new Auth_Adapter ( $email, $password ) );
                if ($result->isValid () === false) {
                    // Brutual force protection:
                    Auth_Adapter::sleep();
                    switch ($result->getCode ()) {
                        case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID :
                            throw new Exception ( '[LS_AUTH_SIGNIN_ERROR]' );
                        case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND :
                            throw new Exception ( '[LS_AUTH_SIGNIN_ERROR]' );
                    }
                    throw new Exception ( '[LS_AUTH_SIGNIN_ERROR]' );
                }
                $url = new Zend_Session_Namespace ( 'Authentication' );
                if (isset ( $url->backUrl )) {
                    $backUrl = $url->backUrl;
                    unset ( $url->backUrl );
                    $this->_redirect ( $backUrl );
                } else {
                    $role = $auth->getUser ()->getRole ();
                    switch ($role) {
                        case 'editor' :
                        case 'advanced_editor' :
                            $this->_helper->redirector ( 'index', 'editor' );
                            break;
                        case 'manager' :
                            $this->_helper->redirector ( 'index', 'manager' );
                            break;
                        default :
                            $this->_helper->redirector ( 'index', 'index' );
                    }
                }
                return;
            } catch ( Exception $e ) {
                $form->addErrorMessage ( $this->view->translate ( $e->getMessage () ) );
            }
        }

        $this->view->signinForm = $form;
    }

    /**
     * Сброс авторизации (Выход)
     */
    public function signoutAction()
    {
        $this->_helper->viewRenderer->setNoRender ( true );
        Auth::getInstance ()->clearIdentity ();
        $this->_redirect ('');
    }

    /**
     * Форма ввода нового пароля
     *
     * Пользователь может ввести новый пароль после перехода по ссылке, которая
     * приходи ему на почту
     * @return void
     */
    public function recoverPasswordAction()
    {
        Auth::getInstance ()->clearIdentity ();

        $form = new Form_User_RecoverPassword();
        try {
            $userId = $this->_getParam ( 'id', false );
            if ($userId === false) {
                throw new Exception ( 'No user id!' );
            }
            $code = $this->_getParam ( 'code', false );
            if ($code === false) {
                throw new Exception ( 'No check code!' );
            }

            $users = new Users ( );
            $user = $users->getObjectById ( $userId );
            if ($user === false) {
                throw new Exception ( 'User not found!' );
            }
            $chkCode = md5 ( 'recover' . $user->getPassword () . 'password' );
            if ($chkCode !== $code) {
                throw new Exception ( 'Wrong check code!' );
            }
        } catch ( Exception $e ) {
            return $this->render ( 'recover-password-failed' );
        }

        if ($this->getRequest ()->isPost ()){
            try {
                if ($form->isValid ( $_POST ) === false) {
                    throw new Exception ( '[LS_VALIDATTION_FORM_FAILED]' );
                }

                // save new password:
                $encryptedPassword = Auth_Adapter::getEncodedPassword ( $user->getEmail (), $form->password->getValue () );
                $user->setPassword ( $encryptedPassword );
                $user->save ();

                return $this->render ( 'recover-password-success' );
            } catch ( Exception $e ) {
                $form->addErrorMessage ( $this->view->translate ( $e->getMessage () ) );
            }
        }

        $this->view->form = $form;
    }

    /**
     * Действия напоминаия пароля
     *
     * @return void
     */
    public function forgetPasswordAction()
    {
        Auth::getInstance ()->clearIdentity ();

        $form = new Form_User_ForgetPassword();
        if ($this->getRequest ()->isPost ()){
            try {
                if ($form->isValid ( $_POST ) === false) {
                    throw new Exception ( '[LS_VALIDATTION_FORM_FAILED]' );
                }

                $email = $form->email->getValue ();
                $users = new Users ( );
                $user = $users->getUserByEmail ( $email );
                if ($user === false) {
                    throw new Exception ( sprintf ( $this->view->translate ( '[LS_USEREMAIL_MISSED]' ), $email ) );
                }
                if ($user->getStatus () !== User::STATUS_ACTIVE) {
                    throw new Exception ( sprintf ( $this->view->translate ( '[LS_USEREMAIL_IS_BLOCKED]' ), $email ) );
                }

                // send email:
                $config = Zend_Registry::getInstance ()->get ( 'config' );
                $mail = new Zend_Mail ( 'UTF-8' );
                $mail->addTo ( $user->getEmail () );
                $mail->setFrom ( $config->administration->email, $config->administration->title );

                $mail->setSubject ( 'Изменение пароля' );
                $viewMail = new Zht_View_Email ( );
                $viewMail->nickname = $user->getNickname ();
                $viewMail->id = $user->getId ();
                $viewMail->code = md5 ( 'recover' . $user->getPassword () . 'password' );
                $viewMail->host = $this->_request->getHttpHost ();
                $mail->setBodyText ( $viewMail->render ( 'user/recover-password-email-text.phtml' ) );
                $mail->setBodyHtml ( $viewMail->render ( 'user/recover-password-email-html.phtml' ) );
                $mail->send ();

                return $this->render ( 'forget-password-success' );

            } catch ( Exception $e ) {
                $form->addErrorMessage ( $this->view->translate ( $e->getMessage () ) );
            }
        }
        $this->view->form = $form;
    }


}