<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */


/**
 * Доработка стандартного Zend_Auth
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Auth extends Zend_Auth {
    /**
     * Guest Identity
     *
     */
    const GUEST_IDENTITY = 1;
    /**
     * Current user
     *
     * @var User
     */
    private $_user = null;
    /**
     * Site Acl
     *
     * @var Zend_Acl
     */
    private $_acl = null;

    /**
     * @see Zend_Auth::getInstance()
     * @return Auth
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self ( );
        }

        return self::$_instance;
    }
    /**
     * @see Zend_Auth::clearIdentity()
     */
    public function clearIdentity()
    {
        parent::clearIdentity ();
        $this->_user = null;
    }

    /**
     * Get User
     *
     * @return User
     */
    public function getUser()
    {
        if (is_null ( $this->_user )) {
            $users = new Users ( );
            if ($this->hasIdentity ()) {
                $this->_user = $users->getObjectById ( $this->getIdentity () );
            } else {
                $this->_user = $users->getObjectById ( self::GUEST_IDENTITY );
            }
        }
        return $this->_user;
    }
    /**
     * Check if current user has enough privileges
     *
     * @param $resource string
     * @param $privilege string
     * @return boolean
     */
    public function isAllowed($resource, $privilege = null)
    {
        return $this->_acl->isAllowed ( $this->getUser ()->getRole (), $resource, $privilege );
    }


    /**
     * Is the current user is identified as given one
     *
     * @param $user User
     * @return boolean
     */
    public function isIdentifiedAs(User $user)
    {
        return $this->getIdentity () === $user->getId ();
    }

    /**
     * Установить acl
     * @param Acl $acl
     * @return Auth
     */
    public function setAcl($acl)
    {
        if ($this->hasIdentity()) {
            $acl->allow('guest','autharea','signout');
            $acl->deny('guest','autharea', array('signin', 'signup'));
        }
        $this->_acl = $acl;
        return $this;
    }

    /**
     * Получить acl
     * @return Acl
     */
    public function getAcl()
    {
        return $this->_acl;
    }

}
