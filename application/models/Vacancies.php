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
     * Row Class
     * @var string
     */
    protected $_rowClass = 'Vacancy';

}

