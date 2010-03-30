<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Таблица вопросов
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Questions extends Zht_Db_Table
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'test_question';

    /**
     * Row Class
     * @var string
     */
    protected $_rowClass = 'Question';

    /**
     * Первичный ключ
     * @var string
     */
    protected $_primary = 'tq_id';

    /**
     * Get Question By questionId
     *
     * @param int $questionId
     * @return array $objQuestion | bool
     */
    public function getQuestionById($questionId)
    {
        $where = array (
                'tq_id=?' => $questionId );
        $objQuestion = $this->fetchRow ( $where );
        if (is_null ( $objQuestion )) {
            return false;
        }
        return $objQuestion;
    }

    /**
     * Get array of Answrs By questionId
     *
     * @param int $questionId
     * @return array arrAnswer | bool  массив вариантов ответов
     */
    public function getAnswerListByQuestionId($questionId)
    {
        $objAnswers = new Answers();
        $arrAnswer = $objAnswers -> getAnswerListByQuestionId( $questionId );
        if (is_null ( $arrAnswer )) {
            return false;
        }
        return $arrAnswer;
    }

    /**
     * Save array of Answers
     *
     * @param int $questionId
     * @param array $arrAnswer массив вариантов ответов
     * @return int $intAddedAnswerAmount количество внесенных в БД ответов
     */
    public function saveAnswerList( $questionId, array $arrAnswer = array() )
    {
        $objAnswer = new Answers();
        $intAddedAnswerAmount = $objAnswer ->
            saveAnswerList( $questionId, $arrAnswer );
        return $intAddedAnswerAmount;
    }

    /**
     * Removes Question By QuestionId
     *
     * @param string $questionId
     * @return $intMaxSortIndex
     */
    public function removeQuestionById($questionId)
    {
        $objQuestion = $this -> getQuestionById( $questionId );
        $intSortIndex = $objQuestion -> getSortIndex();

        // Удаляем ответы для текущего вопроса
        $objAnswers = new Answers();
        $objAnswers -> removeAnswersByQuestionId( $questionId );

        // Удаляем данные о вопросе из БД
        $where = array (
                'tq_id=?' => $questionId );
        $this -> delete( $where );

        // Обновляем порядок сортировки
        $data = array(
            'tq_sort_index' => new Zend_Db_Expr( 'tq_sort_index - 1' ) );
        $where = $this -> getAdapter() ->
            quoteInto('tq_sort_index > ?', $intSortIndex );
        $this -> update( $data, $where );

    }

    /**
     * Moves Question Up (in sort order)
     *
     * @param string $questionId
     * @return $intMaxSortIndex
     */
    public function moveQuestionUp($questionId, $testId)
    {
        $objQuestion = $this -> getQuestionById( $questionId );
        $intSortIndex = $objQuestion -> getSortIndex();

        if ($intSortIndex > 1) {
            $intNewSortIndex  = $intSortIndex - 1;

            // Обновляем инднекс сортировки предыдущего елемента
            $data = array( 'tq_sort_index' => $intSortIndex );
            $where = $this -> getAdapter() ->
                quoteInto( 'tq_sort_index  = ? ', $intNewSortIndex ).
                $this -> getAdapter() ->
                quoteInto( 'AND t_id = ?', $testId );
            $this -> update( $data, $where );

            // Обновляем инднекс сортировки текущего елемента
            $data = array( 'tq_sort_index' => $intSortIndex - 1 );
            $where = $this -> getAdapter() ->
                quoteInto( 'tq_id  = ?', $questionId );
            $this -> update( $data, $where );
        }
    }

    /**
     * Moves Question Down (in sort order)
     *
     * @param int $questionId
     * @return void
     */
    public function moveQuestionDown($questionId, $testId)
    {
        $objQuestion = $this -> getQuestionById( $questionId );
        $testId = $objQuestion -> t_id;
        $intSortIndex = $objQuestion -> getSortIndex();

//        $query = 'SELECT max(tq_sort_index) from test_question';
//        $intMaxSortIndex = $this -> getAdapter() -> fetchOne( $query );
        $intMaxSortIndex = $this -> getMaxSortIndex( $testId );

        if ($intSortIndex < $intMaxSortIndex) {
            $intNewSortIndex  = $intSortIndex + 1;

            // Обновляем инднекс сортировки следующего елемента
            $data = array('tq_sort_index' => $intSortIndex);
            $where = $this -> getAdapter() ->
                quoteInto( 'tq_sort_index  = ? ', $intNewSortIndex).
                $this -> getAdapter() ->
                quoteInto( 'AND t_id = ?', $testId );
            $this -> update( $data, $where );

            // Обновляем инднекс сортировки текущего елемента
            $data = array('tq_sort_index' => $intSortIndex + 1);
            $where = $this -> getAdapter() ->
                quoteInto( 'tq_id  = ?', $questionId );
            $this -> update( $data, $where );
        }
    }

    /**
     * Get Maximum sort index
     *
     * @param int $testId
     * @return $intMaxSortIndex
     */
    public function getMaxSortIndex( $testId )
    {
        $select = $this -> select();
        $select -> from( $this -> _name, 'max(tq_sort_index) as maxIndex' ) ->
                   where( 't_id = ?', $testId );
        $arrResult = $this -> fetchAll( $select ) -> toArray();
        $intMaxSortIndex = ( int ) $arrResult[0]['maxIndex'];
        return $intMaxSortIndex;
    }

    /**
     * Удаление вопросов, соответсвующих заданному тесту (по $testId )
     *
     * @param int $testId
     * @return void
     */
    public function removeQuestionsByTestId( $testId )
    {
        $arrQuestionsId = $this -> _getQuestionIdListByTestId( $testId );

        if ( !empty( $arrQuestionsId ) ) {
            // Удаляем ответы для вопросов теста
            $objAnswers = new Answers();
            $objAnswers -> removeAnswersByQuestionIdList( $arrQuestionsId );

            // Удаляем вопросы теста
            $where = array ( 'tq_id IN ( ? )' => implode(', ', $arrQuestionsId));
            $this -> delete ( $where );
        }
    }
    /**
     * Get array of Questions by TestId
     *
     * @param string $testId
     * @return array $arrQuestion массив вопросов
     */
    public function getQuestionListByTestId( $testId )
    {
        $select = $this -> select() ->
                           where( 't_id = ?', $testId) ->
                           order( 'tq_sort_index' );
        $arrQuestion =  $this -> fetchAll( $select ) -> toArray();

        if (is_null ( $arrQuestion )) {
            return false;
        }
        return $arrQuestion;
    }

    /**
     * Get array of Questions Id by TestId
     *
     * @param string $testId
     * @return array $arrQuestionId массив id вопросов
     */
    protected function _getQuestionIdListByTestId($testId)
    {
        $select = $this -> select() ->
                           from ( $this-> _name, 'tq_id' ) ->
                           where( 't_id = ?', $testId);
        $arrResult = $this -> fetchAll( $select ) -> toArray();
        $arrQuestionId = array();
        foreach ($arrResult as $arrRow)
        {
            $arrQuestionId[] = $arrRow['tq_id'];
        }

        if (is_null ( $arrQuestionId )) {
            return false;
        }
        return $arrQuestionId;
    }
}