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
    protected $_name = 'mg_test_question';

    /**
     * Row Class
     * @var string
     */
    protected $_rowClass = 'Question';

    /**
     * Get Question By questionId
     *
     * @param string $questionId
     * @return Question | boolean
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
     * @param string $questionId
     * @return arrAnswer | array
     */
    public function getAnswerListByQuestionId($questionId)
    {
        $query = "SELECT * FROM mg_test_question_answer "
               . "WHERE mg_test_question_answer.tq_id = $questionId";

        $arrAnswer = $this -> getAdapter()-> fetchAll($query);

        if (is_null ( $arrAnswer )) {
            return false;
        }
        return $arrAnswer;
    }

    /**
     * Save array of Answers
     *
     * @param array arrAnswer
     * @return arrAnswer | array
     */
    public function saveAnswerList($questionId, array $arrAnswer = array())
    {
        if (!$questionId) {
            $questionId =$this -> getAdapter()-> lastInsertId(); 
        }
        // Удаляем ответы для текущего вопроса
        $this -> getAdapter()-> delete('mg_test_question_answer',
            'tq_id = '.$questionId);
        foreach ($arrAnswer as $arrAnswerItem) {
            $data = array(
                    'tqa_text'      =>  $arrAnswerItem['text'],
                    'tqa_flag'      =>  array_key_exists( 'flag', $arrAnswerItem )?1:0,
                    'tq_id'         =>  $questionId
            );
            $this -> getAdapter()-> insert('mg_test_question_answer', $data );
        }
        return true;
    }

    /**
     * Removes Question By QuestionId
     *
     * @param string $questionId
     */
    public function removeQuestionById($questionId)
    {
        $objQuestion = $this -> getQuestionById( $questionId );
        $intSortIndex = $objQuestion -> getSortIndex();

        // Удаляем ответы для текущего вопроса
        $this -> getAdapter()-> delete('mg_test_question_answer',
            'tq_id = '.$questionId);

        // Удаляем данные о вопросе из БД
        $where = array (
                'tq_id=?' => $questionId );
        $this -> delete( $where );

        // Обновляем порядок сортировки
        $data = array('tq_sort_index' => new Zend_Db_Expr('tq_sort_index - 1'));
        $this -> getAdapter() ->
            update('mg_test_question', $data, "tq_sort_index > $intSortIndex");

    }

    /**
     * Moves Question Up (in sort order)
     *
     * @param string $questionId
     */
    public function moveQuestionUp($questionId)
    {
        $objQuestion = $this -> getQuestionById( $questionId );
        $intSortIndex = $objQuestion -> getSortIndex();

        if ($intSortIndex > 1) {
            $intNewSortIndex  = $intSortIndex - 1;

            // Обновляем инднекс сортировки предыдущего елемента
            $data = array('tq_sort_index' => $intSortIndex);
            $this -> getAdapter() -> update('mg_test_question', $data,
                "tq_sort_index  = $intNewSortIndex" );

            // Обновляем инднекс сортировки текущего елемента
            $data = array('tq_sort_index' => $intSortIndex - 1);
            $this -> getAdapter() -> update('mg_test_question', $data,
                'tq_id  = '.$questionId );
        }
    }

    /**
     * Moves Question Down (in sort order)
     *
     * @param string $questionId
     */
    public function moveQuestionDown($questionId)
    {
        $objQuestion = $this -> getQuestionById( $questionId );
        $query = 'SELECT max(tq_sort_index) from mg_test_question';
        $intMaxSortIndex = $this -> getAdapter() -> fetchOne( $query );
        $intSortIndex = $objQuestion -> getSortIndex();

        if ($intSortIndex < $intMaxSortIndex) {
            $intNewSortIndex  = $intSortIndex + 1;

            // Обновляем инднекс сортировки следующего елемента
            $data = array('tq_sort_index' => $intSortIndex);
            $this -> getAdapter() -> update('mg_test_question', $data,
                "tq_sort_index  = $intNewSortIndex" );

            // Обновляем инднекс сортировки текущего елемента
            $data = array('tq_sort_index' => $intSortIndex + 1);
            $this -> getAdapter() -> update('mg_test_question', $data,
                'tq_id  = '.$questionId );
        }
    }
}

