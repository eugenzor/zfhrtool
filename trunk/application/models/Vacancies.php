<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Таблица вакансий
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Vacancies extends Zht_Db_Table
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'vacancies';

    /**
     * Имя таблицы, которое изпользуеться при join
     * @var string
     */
    const  NAME = 'vacancies';

    /**
     * Row Class
     * @var string
     */
    protected $_rowClass = 'Vacancy';

}

