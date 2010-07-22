<?php
/**
 * @package zfhrtool
 * @subpackage Model
 */

/**
 * Таблица ответов 
 *
 * @package zfhrtool
 * @subpackage Model
 */
class Answers extends Zht_Db_Table
{
    /**
     * Имя таблицы
     * @var string
     */
    protected $_name = 'test_question_answer';

    /**
     * Имя таблицы, которое изпользуеться при join
     * @var string
     */
    const  NAME = 'test_question_answer';

    /**
     * Row Class
     * @var string
     */
    protected $_rowClass = 'Answer';

    /**
     * Первичный ключ
     * @var string
     */
    protected $_primary = 'tqa_id';

    /**
     * Get array of Answers By questionId
     *
     * @param int $questionId
     * @return array arrAnswer массив вариантов ответов
     */
    public function getAnswerListByQuestionId( $questionId )
    {
        $select = $this -> select();
        $select -> where( 'tq_id = ?', $questionId );
        $arrAnswer = $this -> fetchAll( $select ) -> toArray();

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
        if (!$questionId) {
            $questionId =$this -> getAdapter()-> lastInsertId();
        }
        // Удаляем ответы для текущего вопроса
        $where = array (
                'tq_id=?' => $questionId );
        $this -> delete( $where );
        // Вносим новые ответы в БД
        $intAffectedRows = 0;
        foreach ($arrAnswer as $arrAnswerItem) {
            $data = array(
                    'tqa_text'      =>  $arrAnswerItem['text'],
                    'tqa_flag'      =>  $arrAnswerItem['flag']?1:0,
                    'tq_id'         =>  $questionId
            );
            if ( !empty( $data['tqa_text'] ) ) {
                $intAffectedRows++;
                $this -> insert( $data );
            }
        }
        return $intAffectedRows;
    }

    /**
     * Removes Answers By QuestionId
     *
     * @param int $questionId
     * @return void
     */
    public function removeAnswersByQuestionId( $questionId )
    {
        $where = array (
                'tq_id=?' => $questionId );
        $this -> delete( $where );
    }

    /**
     * Removes Answers By array of questionId
     *
     * @param array $arrQuestionId
     * @return void
     */
    public function removeAnswersByQuestionIdList( array $arrQuestionId )
    {
        if ( !empty( $arrQuestionId ) ) {
            $where =
                array ( 'tq_id IN ( ? )' => implode( ', ', $arrQuestionId ) );
            $this -> delete( $where );
        }
    }

    /**
     * Возвращает варианты ответов
     *
     * @param array $questionIds массив с Id вопросов
     * @return array массив вариантов ответов
     */
    public function getAnswers( $questionIds )
    {
        $answers = $this -> fetchAll($this -> select()
                    -> where('tq_id IN (?)', $questionIds))
                -> toArray();
        return (array) $answers;


    }

}