<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Базовая навигация для проекта
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Navigation extends Zend_Navigation
{
    /**
     * Базовое меню
     * @var array
     */
    protected $_menu = array(
        array(
            'label'=>'Главная',
            'controller'=>'index'
        ),
        array(
            'label'=>'Войти',
            'controller'=>'user',
            'action' => 'signin'
        ),
        array(
            'label'=>'Зарегистрироваться',
            'controller'=>'user',
            'action' => 'signup'
        ),
        array(
            'label'=>'Тесты',
            'controller'=>'test',
            'action' => 'index',
        ),
        array(
            'label'=>"Выйти",
            'controller'=>'user',
            'action' => 'signout'
        )
    );

    /**
     * Устанавливаем базовое меню
     */
    public function __construct()
    {
        parent::__construct($this->_menu);
    }
}