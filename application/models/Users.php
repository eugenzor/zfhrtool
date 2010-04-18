<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Объект таблицы пользователей
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Users extends Zht_Db_Table
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'users';

    /**
     * Row Class
     * @var string
     */
    protected $_rowClass = 'User';

    /**
     * Get User By email
     *
     * @param string $email
     * @return User | boolean
     */
    public function getUserByEmail($email) {
        $where = array (
                'email=?' => $email );
        $user = $this->fetchRow ( $where );
        if (is_null ( $user )) {
            return false;
        }
        return $user;
    }
    /**
     * Get User By nickname
     *
     * @param string $email
     * @return User | boolean
     */
    public function getUserByNickname($login) {
        $where = array (
                'nickname=?' => $login );
        $user = $this->fetchRow ( $where );
        if (is_null ( $user )) {
            return false;
        }
        return $user;
    }

}

