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
    protected $_name = 'test';

    /**
     * Имя таблицы, которое изпользуеться при join
     * @var string
     */
    const  NAME = 'test';

    /**
     * Row Class
     * @var string
     */
    protected $_rowClass = 'Test';

    /**
     * Dependent tables
     * @var array
     */
    protected $_dependentTables = array('QuestionCategories');

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
     * @param int $testId
     * @param string $strTestFilter строка поиска (фильтрации)
     * @return arrTest | array 
     */
    public function getTestListByCategoryId($categoryId, $strTestFilter = null)
    {
        $select = $this -> getAdapter()-> select() -> from( $this->_name );
        if ( -1 != $categoryId ) {
            $select -> where( 'test.cat_id = ?', $categoryId );
        }
        $select -> join( 'category', 'category.cat_id = test.cat_id' );
        if ($strTestFilter) {
            $select -> where( 't_name LIKE ?', "%$strTestFilter%" );
        }

        $stmt = $this -> getAdapter() -> query($select);
        $arrTest = $stmt -> fetchAll(Zend_Db::FETCH_OBJ);

        if (is_null ( $arrTest )) {
            return false;
        }
        return $arrTest;
    }

    /**
     * Removes Test By TestId
     *
     * @param int $testId
     * @return void
     */
    public function removeTestById($testId)
    {
        $objQuestions = new Questions();
        $objQuestions -> removeQuestionsByTestId( $testId );
        
        /*$objQuestionCategories = new QuestionCategories();
        $objQuestionCategories -> removeCategoriesByTestId( $testId );*/

        // Удаляем информацию о тесте из БД
        $where = array (
                't_id=?' => $testId );
        $this -> delete( $where );
    }

    /**
     * Get array of Questions by TestId
     *
     * @param int $testId
     * @return array $arrQuestion массив вопросов
     */
    public function getQuestionListByTestId( $testId )
    {
        $objQuestions = new Questions();
        return $objQuestions -> getQuestionListByTestId( $testId );
    }

    /**
     * Get array of Question Categories by TestId
     *
     * @param int $testId
     * @return array | bool
     */
    public function getQuestionCategoriesListByTestId( $testId )
    {
        $objQuestionsCategories = new QuestionCategories();
        return $objQuestionsCategories -> getCategoryShortListByTestId( $testId );
    }

    /**
     * Пересчитывает количество вопросов в тесте
     *
     * @param int $testId
     * @return void
     */
    public function recalculationQuestions($testId)
    {
        $questionsTable = Questions::NAME;
        $testTable = $this->_name;
        $this -> getAdapter() -> query("UPDATE {$testTable} "
                                         ."SET t_quest_amount = (SELECT count(*) FROM {$questionsTable} WHERE t_id = {$testTable}.t_id) "
                                       ."WHERE t_id = {$testId}");

    }
    //UPDATE test SET t_quest_amount = (SELECT count(*) FROM `test_question` WHERE t_id=test.t_id) WHERE t_id = 1;
    //UPDATE test_question SET tq_answer_amount = (SELECT count(*) FROM `test_question_answer` WHERE tq_id=test_question.tq_id) WHERE t_id = 1;
}