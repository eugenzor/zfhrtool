<?php

/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Данные прохождения тестов
 *
 * @package zfhrtool
 * @subpackage Model
 */
class ApplicantTests extends Zht_Db_Table {

    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'applicant_tests';

    /**
     * Имя таблицы, которое изпользуеться при join
     * @var string
     */
    const  NAME = 'applicant_tests';


    /**
     * Проверяет существование Link в базе данных
     *
     * @param integer $applicantId Id соискателя.
     * @param integer $testId Id теста.
     * @return bool
     */
    public function existLink($applicantId, $testId) {
        $link = $this -> fetchAll($this -> select()
                                -> where('applicant_id = ?', (int) $applicantId)
                                -> where('test_id = ?', (int) $testId)
                                -> where('link is not NULL')
                                -> where('date is NULL')
                                -> where('percent is NULL'))
                     -> count();
        return (boolean) $link;
    }

    /**
     * Возвращает link на тестирование
     *
     * @param integer $applicantId Id соискателя.
     * @param integer $testId Id теста.
     * @return string link на тестирование
     */
    public function getLink($applicantId, $testId)
    {
        $select = $this -> getAdapter()-> select()
         -> from( $this->_name, 'link')
         -> where( 'applicant_id = ?', (int) $applicantId)
         -> where( 'test_id = ?', (int) $testId)
         -> where( 'link is not NULL')
         -> where( 'date is NULL')
         -> where( 'percent is NULL');
        $link = $this -> getAdapter() -> query($select) -> fetchColumn();;
        return $link;
    }

    /**
     * Возвращает максимальный процент (правильных ответов) пройденых тестировань
     *
     * @param integer $applicantId Id соискателя.
     * @param integer $testId Id теста.
     * @return integer максимальный процент
     */
    public function getMaxPercent($applicantId, $testId)
    {
        $select = $this -> getAdapter()-> select()
         -> from( $this->_name, 'max(percent)')
         -> where( 'applicant_id = ?', (int) $applicantId)
         -> where( 'test_id = ?', (int) $testId);
        $percent = $this -> getAdapter() -> query($select) -> fetchColumn();
        return $percent;
    }

    /**
     * Возвращает по проценту (правильных ответов) link на пройденое тестирование
     *
     * @param integer $applicantId Id соискателя.
     * @param integer $testId Id теста.
     * @param integer $percent процент (правильных ответов) пройденого тестирования
     * @return string link на пройденое тестирование
     */
    public function getLinkForPercent($applicantId, $testId, $percent)
    {
        if (is_null($percent)) return null;
        $select = $this -> getAdapter()-> select()
         -> from( $this->_name, 'link')
         -> where( 'applicant_id = ?', (int) $applicantId)
         -> where( 'test_id = ?', (int) $testId)
         -> where( 'percent = ?', (int) $percent);
        $link = $this -> getAdapter() -> query($select) -> fetchColumn();
        return $link;
    }

    /**
     * Возвращает тест (строка с ApplicantTests)
     *
     * @param string $link link на тестирование
     * @return ApplicantTests Row
     */
    public function getTest($link)
    {
        $applicantTest = $this -> fetchRow($this -> select()
                -> where('link = ?', (string) $link));
        return $applicantTest;
    }




}
