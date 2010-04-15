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
            'label'=>'Вакансии',
            'controller'=>'vacancy',
            'action' => 'index',
            'resource' => 'vacancies',
            'privilege' => 'view',
            'pages' => array(
                array(
                    'label'=>'Список вакансий',
                    'controller'=>'vacancy',
                    'action' => 'index',
                ),
                array(
                    'label'=>'Добавить вакансию',
                    'controller'=>'vacancy',
                    'resource' => 'vacancies',
                    'privilege' => 'add',
                    'action' => 'edit',
                ),
            )
        ),
        array(
            'label'=>'Соискатели',
            'controller'=>'applicant',
            'action' => 'index',
            'resource' => 'applicants',
            'privilege' => 'view',
            'pages' => array(
                array(
                    'label'=>'Показать список',
                    'controller'=>'applicant',
                    'action' => 'index',
                ),
                array(
                    'label'=>'Добавить соискателя',
                    'controller'=>'applicant',
                    'resource' => 'applicants',
                    'privilege' => 'add',
                    'action' => 'add',
                ),
            )
            ),
        array(
            'label' => 'Пользователи',
            'controller' => 'user',
            'resource' => 'users',
            'privilege' => 'view',
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