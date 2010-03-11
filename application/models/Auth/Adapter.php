<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Authentication Process
 *
 * The class is required for Zend_Auth authentication process.
 * It checks user login and password.
 *
 * @see Zend_Auth
 * @package Security
 * @subpackage Authentication
 */
class Auth_Adapter implements Zend_Auth_Adapter_Interface {
    const AUTH_KEY = 'SecretKey';
    private $_authResult = Zend_Auth_Result::FAILURE;
    private $_idUser = null;

    static $sleepTime = 3;

    /**
     * Construct the object.
     *
     * Construct object, with checking user credentials,
     * setup the result code, and user identity for futher usage.
     *
     * @param string $login
     * @param string $password
     */
    public function __construct($login, $password) {
        try {
            $users = new Users ( );
            $user = $users->getUserByEmail ( $login );
            if ($user === false) {
                throw new Zend_Auth_Adapter_Exception ( 'Identity not found', Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND );
            }
            if($user->getStatus() !== User::STATUS_ACTIVE) {
                throw new Zend_Auth_Adapter_Exception ( 'User account is not active', Zend_Auth_Result::FAILURE_UNCATEGORIZED );
            }

            $encodedPassword = self::getEncodedPassword ( $login, $password );

            if ($encodedPassword !== $user->getPassword ()) {
                throw new Zend_Auth_Adapter_Exception ( 'Password is invalid', Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID );
            }

            $this->_idUser = $user->getId();
            $this->_authResult = Zend_Auth_Result::SUCCESS;

            $user->setLastLoginAt();
            $user->save();

        } catch ( Exception $e ) {
            $this->_authResult = $e->getCode ();
        }
    }
    /**
     * Get encoded password
     *
     * @param string $login
     * @param string $password
     * @return string
     */
    public static function getEncodedPassword($login, $password) {
        $encodedPassword = md5 ( $login . self::AUTH_KEY . $password );
        return $encodedPassword;
    }
    /**
     * Authentication.
     *
     * Just return the zend authentication result object with result code,
     * and user identity.
     *
     * @see Zend_Auth_Adapter_Interface
     * @return Zend_Auth_Result
     */
    public function authenticate() {
        return new Zend_Auth_Result ( $this->_authResult, $this->_idUser );
    }

    /**
     * Ожидание
     *
     * Защита от подбора паролей с возможностью изменения времени ожидания
     * @return integer number of seconds left to sleep.
     */
    static public function sleep()
    {
        return sleep(self::$sleepTime);
    }
}
