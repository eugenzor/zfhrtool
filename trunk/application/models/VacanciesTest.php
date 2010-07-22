<?php

/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Таблица связей - какая вакансия какие тесты имеет
 *
 * @package zfhrtool
 * @subpackage Model
 */
class VacanciesTest extends Zht_Db_Table {

    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'vacancies_test';

    /**
     * Имя таблицы, которое изпользуеться при join
     * @var string
     */
    const  NAME = 'vacancies_test';


    /**
     * Возвращает Id тестов
     *
     * @param integer $vacancyId Id вакансии.
     * @return array  массив, значения которого - Id тестов
     */
    public function getTestIds($vacancyId) {
        $tests = $this -> fetchAll($this -> select()
                                         -> where('vacancy_id = ?', $vacancyId))
                      -> toArray();
        $testIds = array();
        foreach ($tests as $test) {
            $testIds[] = $test['test_id'];
        }
        return $testIds;
    }

    /**
     * Возвращает тесты и id вакансии
     *
     * @return array Массив с тестами и id вакансий.
     */
    public function getTestsV() {
        $select = $this -> getAdapter() -> select()
         -> from(array('vt' => $this->_name), array('vacancy_id' => 'vacancy_id'))
         -> join(array('t' => Tests::NAME), 'vt.test_id = t.t_id', array('t_name' => 't_name'));
        $tests = $this -> getAdapter() -> query($select) -> fetchAll();
        return $tests;
    }

    /**
     * Возвращает тесты и id соискателей
     *
     * @return array Массив в которого ключи - id соискателей, а значение - массив с тестами.
     */
    public function getTestsA()
    {
        $select = $this -> getAdapter() -> select()
         -> from( array('a' => Applicants::NAME),                      array('applicantId'=>'id'))
         -> join( array('v' => Vacancies::NAME),'a.vacancy_id = v.id', array())
         -> join( array('vt'=> $this->_name),   'vt.vacancy_id = v.id',array())
         -> join( array('t' => Tests::NAME),    'vt.test_id = t.t_id', array('testId'=>'t_id','testName'=>'t_name'));
        $tests = $this -> getAdapter() -> query($select) -> fetchAll();
        $objAT = new ApplicantTests();
        $arrTest = array();
        foreach($tests as $test)
        {
            $link = $objAT -> getLink($test['applicantId'], $test['testId']);
            $percentMax = $objAT -> getMaxPercent($test['applicantId'], $test['testId']);
            $linkMaxPercent = $objAT -> getLinkForPercent($test['applicantId'], $test['testId'], $percentMax);
            $arrTest[$test['applicantId']][] = array('id'    => $test['testId'],
                                                      'name' => $test['testName'],
                                                      'link' => $link,
                                                      'linkMaxPercent' => $linkMaxPercent,
                                                      'percentMax' => $percentMax);
        }
        return $arrTest;
    }

    

}

