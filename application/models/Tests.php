<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Таблица тестов
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Tests extends Zht_Db_Table
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'mg_test';

    /**
     * Row Class
     * @var string
     */
    protected $_rowClass = 'Test';

    /**
     * Get Test By TestId
     *
     * @param string $testId
     * @return Test | boolean
     */
    public function getTestById($testId)
    {
        $where = array (
                't_id=?' => $testId );
        $objTest = $this->fetchRow ( $where );
        if (is_null ( $objTest )) {
            return false;
        }
        return $objTest;
    }

    /**
     * Get array of Tests By TestId
     *
     * @param string $testId
     * @return arrTest | array
     */
    public function getTestListByCategoryId($categoryId, $strTestFilter = null)
    {
        if ($categoryId != -1) {
            $query = "SELECT * FROM $this->_name, mg_category "
                   . "WHERE {$this->_name}.cat_id = mg_category.cat_id "
                   . "AND {$this->_name}.cat_id = $categoryId";
        } else {
            $query = "SELECT * FROM $this->_name, mg_category "
                   . "WHERE {$this->_name}.cat_id = mg_category.cat_id";
        }

        if ($strTestFilter) {
            $query .= " AND {$this->_name}.t_name LIKE '%$strTestFilter%'";
        }

        $arrTest = $this -> getAdapter()-> fetchAll($query);

        if (is_null ( $arrTest )) {
            return false;
        }
        return $arrTest;
    }

    /**
     * Removes Test By TestId
     *
     * @param string $testId
     * @return intResult | bool
     */
    public function removeTestById($testId)
    {
        $arrQuestionsId = $this -> getQuestionIdListByTestId( $testId );

        if ( !empty( $arrQuestionsId ) ) {
            // Удаляем ответы для вопросов теста
            $this -> getAdapter()-> delete('mg_test_question_answer',
                'tq_id IN( ' . implode(', ', $arrQuestionsId) . ' )' );

            // Удаляем вопросы теста
            $this -> getAdapter()-> delete('mg_test_question',
                'tq_id IN( ' . implode(', ', $arrQuestionsId) . ' )' );
        }

        // Удаляем информацию о тесте из БД
        $where = array (
                't_id=?' => $testId );
        $this -> delete( $where );
    }

    public function getQuestionListByTestId($testId)
    {
        $query = "SELECT * FROM mg_test_question "
               . "WHERE mg_test_question.t_id = {$testId} "
               . "ORDER BY mg_test_question.tq_sort_index";

        $arrQuestion = $this -> getAdapter()-> fetchAll($query);

        if (is_null ( $arrQuestion )) {
            return false;
        }
        return $arrQuestion;
    }

    public function getQuestionIdListByTestId($testId)
    {
        $query = "SELECT tq_id FROM mg_test_question "
               . "WHERE mg_test_question.t_id = ?";

        $arrQuestionId = $this -> getAdapter()->
            fetchAll($query, $testId, Zend_Db::FETCH_COLUMN);

        if (is_null ( $arrQuestionId )) {
            return false;
        }
        return $arrQuestionId;
    }
}